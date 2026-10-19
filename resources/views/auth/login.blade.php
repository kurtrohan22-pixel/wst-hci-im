@extends('layouts.app')

@section('title', 'Log in')
@section('content')
<section class="auth-section"><div class="auth-card">
    <div class="eyebrow"><span class="eyebrow-dot"></span> WELCOME BACK</div>
    <h1>Pick up where<br><em>you left off.</em></h1>
    <p class="muted">Log in to manage your portfolios.</p>
    @if ($errors->any())<div class="form-alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login.store') }}" class="form-stack">@csrf
        <label>Email address<input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"></label>
        <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
        <label class="check-label"><input type="checkbox" name="remember"> Keep me logged in</label>
        <button class="button button-dark button-full" type="submit">Log in <span aria-hidden="true">↗</span></button>
    </form>
    <p class="auth-foot">New to Folioform? <a href="{{ route('register') }}">Create an account</a></p>
</div></section>
@endsection
