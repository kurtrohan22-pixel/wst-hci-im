$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$envPath = Join-Path $projectRoot '.env'
$hostName = 'aws-0-ap-southeast-1.pooler.supabase.com'
$databaseUser = 'postgres.ybgxxqlkexxfrmkhmouj'

if (-not (Test-Path $envPath)) {
    throw 'The .env file was not found. Copy .env.example to .env and run php artisan key:generate first.'
}

$pdoExtension = php -d extension=php_pdo_pgsql.dll -m 2>&1
if ($LASTEXITCODE -ne 0 -or $pdoExtension -notcontains 'pdo_pgsql') {
    throw 'PHP could not load pdo_pgsql. Verify php_pdo_pgsql.dll is available in the PHP extensions directory.'
}

$securePassword = Read-Host 'Enter the Supabase database password (input is hidden)' -AsSecureString
$passwordPointer = [IntPtr]::Zero

try {
    $passwordPointer = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($securePassword)
    $password = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($passwordPointer)

    if ([string]::IsNullOrWhiteSpace($password) -or $password.Contains("`r") -or $password.Contains("`n")) {
        throw 'A non-empty, single-line database password is required.'
    }

    $escapedPassword = $password.Replace('\', '\\').Replace("'", "\'")
    $envLines = [System.Collections.Generic.List[string]]::new()
    $envLines.AddRange([string[]](Get-Content -Path $envPath))

    $values = [ordered]@{
        DB_CONNECTION = 'pgsql'
        DB_HOST = $hostName
        DB_PORT = '5432'
        DB_DATABASE = 'postgres'
        DB_USERNAME = $databaseUser
        DB_PASSWORD = "'$escapedPassword'"
        DB_SSLMODE = 'require'
    }

    foreach ($entry in $values.GetEnumerator()) {
        $pattern = '^' + [regex]::Escape($entry.Key) + '='
        $index = -1
        for ($i = 0; $i -lt $envLines.Count; $i++) {
            if ($envLines[$i] -match $pattern) {
                $index = $i
                break
            }
        }

        if ($index -ge 0) {
            $envLines[$index] = "$($entry.Key)=$($entry.Value)"
        } else {
            $envLines.Add("$($entry.Key)=$($entry.Value)")
        }
    }

    [System.IO.File]::WriteAllLines($envPath, $envLines, [System.Text.UTF8Encoding]::new($false))
} finally {
    if ($passwordPointer -ne [IntPtr]::Zero) {
        [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($passwordPointer)
    }
    if ($null -ne $password) {
        $password = $null
    }
    if ($null -ne $securePassword) {
        $securePassword.Dispose()
    }
}

Push-Location $projectRoot
try {
    php artisan config:clear
    if ($LASTEXITCODE -ne 0) {
        throw 'Could not clear cached Laravel configuration.'
    }

    php -d extension=php_pdo_pgsql.dll artisan migrate --force
    if ($LASTEXITCODE -ne 0) {
        throw 'The Supabase migration failed. Check the host, password, network access, and PHP PostgreSQL driver.'
    }

    Write-Host 'Supabase is connected and the Laravel database migrations have completed.'
    Write-Host 'Start the local app with: php -d extension=php_pdo_pgsql.dll artisan serve'
} finally {
    Pop-Location
}
