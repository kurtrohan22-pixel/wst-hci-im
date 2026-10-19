@extends('layouts.app')

@section('title', 'Create your account')
@section('content')
<section class="auth-section"><div class="auth-card">
    <div class="eyebrow"><span class="eyebrow-dot"></span> A GOOD PLACE TO BEGIN</div>
    <h1>Make room for<br><em>your next step.</em></h1>
    <p class="muted">Create an account to save and share your portfolio.</p>
    @if ($errors->any())<div class="form-alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('register.store') }}" class="form-stack">@csrf
        <label>Your name<input type="text" name="name" value="{{ old('name') }}" required autocomplete="name"></label>
        <label>Email address<input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
        <label>Password<input type="password" name="password" required autocomplete="new-password" minlength="8"></label>
        <label>Confirm password<input type="password" name="password_confirmation" required autocomplete="new-password"></label>
        <button class="button button-dark button-full" type="submit">Create account <span aria-hidden="true">↗</span></button>
    </form>
    <p class="auth-foot">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
</div></section>
@endsection
