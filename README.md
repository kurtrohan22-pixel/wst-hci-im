# Folioform

Folioform is a Laravel portfolio builder. Users create an account, save their profile and work, choose one of three templates, preview a public page, and manage their portfolio from a private dashboard.

## Features

- Account registration, login, logout, and user-owned portfolios.
- Portfolio details: name, profile picture, email, phone, address, biography, education, skills, projects, work experience, and social links.
- Three distinct Blade portfolio designs: Simple, Modern, and Creative.
- Public portfolio links at `/p/{uuid}`; dashboard routes require sign-in.
- PostgreSQL JSONB data fields for lists of education, skills, projects, experience, and social links.
- Responsive Blade UI styled with Tailwind CSS and application-specific CSS.

## Local development

Requirements: PHP 8.2+, Composer, Node.js/npm, and a database. A local `.env` can use SQLite so the project can be run without cloud credentials.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

For SQLite development, set `DB_CONNECTION=sqlite` in `.env` and create `database/database.sqlite` if it does not exist. Then install frontend dependencies and build assets:

```powershell
New-Item -ItemType File -Force database/database.sqlite
npm install
npm run build
php artisan migrate
php artisan storage:link
php artisan serve
```

The home page will be available at `http://127.0.0.1:8000`.

## Supabase PostgreSQL

This project is provisioned as **Folioform Portfolio Generator**. Its IPv4-compatible Supabase session-pooler settings are included in `.env.example`. To connect this Windows development environment without putting the database password in chat or command history:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\connect-supabase.ps1
```

The script prompts for the database password with hidden input, writes it to the git-ignored `.env`, and runs the migrations using the PostgreSQL driver. If you no longer know the database password, reset it in Supabase **Project Settings → Database** first. To run the local site with PostgreSQL enabled in this PHP process:

```powershell
php -d extension=php_pdo_pgsql.dll artisan serve
```

For a deployment, set the following environment variables in the hosting provider's secret settings (do not commit credentials):

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-public-domain.example
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.ybgxxqlkexxfrmkhmouj
DB_PASSWORD=your-supabase-database-password
DB_SSLMODE=require
```

Enable PHP's `pdo_pgsql` extension on the application host. Run `php artisan migrate --force` during deployment so users and portfolios are created in the hosted database.

Uploaded profile pictures use Laravel's public filesystem by default. On an ephemeral hosting platform, configure durable object storage for uploads before accepting production users. Supabase Storage's S3-compatible endpoint can be used with Laravel's S3 filesystem (`FILESYSTEM_DISK=s3`, `AWS_ENDPOINT`, `AWS_URL`, `AWS_BUCKET`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, and `AWS_DEFAULT_REGION`); make the bucket public for portfolio photos.

## Deployment

This repository includes a Vercel serverless entry point (`api/index.php`) and [`vercel.json`](./vercel.json). Vercel uses the community PHP runtime (`vercel-php@0.9.0`, PHP 8.5), Composer for Laravel dependencies, and `npm run build` to produce the static assets in `public`.

1. Import `kurtrohan22-pixel/OPTG_wst_im_hci` from the Vercel dashboard.
2. Keep the Root Directory at the repository root; use the checked-in build command `npm run build` and output directory `public`.
3. Add the variables from the Supabase section to Vercel **Project → Settings → Environment Variables**, including a fresh, persistent `APP_KEY`, `APP_URL` (the assigned `https://...vercel.app` URL), `APP_ENV=production`, and `APP_DEBUG=false`. Set `SESSION_DRIVER=cookie`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `LOG_CHANNEL=stderr`, and `VIEW_COMPILED_PATH=/tmp` for the serverless runtime.
4. Run `php artisan migrate --force` against Supabase after the environment variables are set. Use a deployment/build command that has the same PostgreSQL connection variables; do not put credentials in `vercel.json` or GitHub.
5. Test registration, login, portfolio CRUD, public previews, and profile image uploads on the deployed URL.

Vercel's function filesystem is ephemeral. Profile pictures currently use Laravel's public local disk, so production image uploads need durable object storage (for example, a public Supabase Storage bucket configured through Laravel's S3 filesystem) before relying on them. The application must also be deployed with a persistent `APP_KEY` so encrypted cookies remain valid across requests.

The public share URL for a portfolio is generated from the configured `APP_URL`, for example `https://your-public-domain.example/p/{uuid}`. A real public deployment URL requires a hosting account, a configured domain/service, and Supabase connection credentials; these are intentionally not included in the source tree.

## Tests

The feature tests use in-memory SQLite and cover the create → template → public preview → edit → delete flow, user ownership, and safe portfolio URLs:

```powershell
php artisan test --filter=PortfolioFlowTest
```
