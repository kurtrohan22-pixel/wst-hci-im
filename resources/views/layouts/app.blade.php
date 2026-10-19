<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Folioform') — Folioform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class')">
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark">f.</span> folioform</a>
            <nav class="site-nav" aria-label="Main navigation">
                @auth
                    <a href="{{ route('portfolios.index') }}">My portfolios</a>
                    <span class="nav-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-link" type="submit">Log out</button></form>
                    <a class="button button-dark button-small" href="{{ route('portfolios.create') }}">Create portfolio <span aria-hidden="true">↗</span></a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    <a class="button button-dark button-small" href="{{ route('register') }}">Get started <span aria-hidden="true">↗</span></a>
                @endauth
            </nav>
        </div>
    </header>

    <main>@yield('content')</main>

    <footer class="site-footer">
        <div class="container footer-inner"><a class="brand" href="{{ route('home') }}"><span class="brand-mark">f.</span> folioform</a><span>Make your next chapter look like you.</span></div>
    </footer>
</body>
</html>
