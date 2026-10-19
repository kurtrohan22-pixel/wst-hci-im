@extends('layouts.app')

@section('title', 'My portfolios')
@section('content')
<section class="dashboard-section"><div class="container">
    @if (session('status'))<div class="success-alert">{{ session('status') }}</div>@endif
    <div class="dashboard-heading"><div><div class="eyebrow"><span class="eyebrow-dot"></span> YOUR WORKSPACE</div><h1>Good things<br>take <em>shape here.</em></h1><p class="muted">Keep your story up to date and ready to share.</p></div><a class="button button-dark" href="{{ route('portfolios.create') }}">Start a portfolio <span aria-hidden="true">↗</span></a></div>
    @if ($portfolios->isEmpty())
        <div class="empty-state"><div class="empty-icon">✳</div><h2>Your first portfolio starts here.</h2><p>Add the details that make your work yours. You can edit everything later.</p><a class="button button-dark" href="{{ route('portfolios.create') }}">Create your first portfolio <span aria-hidden="true">↗</span></a></div>
    @else
        <div class="portfolio-grid">
            @foreach ($portfolios as $portfolio)
                <article class="portfolio-card">
                    <a class="portfolio-card-art art-{{ $portfolio->template }}" href="{{ route('portfolios.show', $portfolio) }}">
                        <span class="portfolio-card-style">{{ ucfirst($portfolio->template) }} edition</span>
                        <span class="portfolio-card-name">{{ $portfolio->full_name }}</span>
                        <span class="portfolio-card-arrow">↗</span>
                        @if ($portfolio->profile_image)<img src="{{ asset('storage/'.$portfolio->profile_image) }}" alt="" class="portfolio-card-avatar">@else<div class="portfolio-card-avatar initials">{{ strtoupper(substr($portfolio->full_name, 0, 1)) }}</div>@endif
                    </a>
                    <div class="portfolio-card-meta"><div><span>{{ count($portfolio->projects ?? []) }} projects</span><span class="meta-separator">·</span><span>Updated {{ $portfolio->updated_at->diffForHumans() }}</span></div><a href="{{ route('portfolios.edit', $portfolio) }}">Edit details <span aria-hidden="true">↗</span></a></div>
                </article>
            @endforeach
        </div>
    @endif
</div></section>
@endsection
