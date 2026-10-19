@extends('layouts.app')

@section('title', $portfolio->full_name . ' portfolio')
@section('content')
<section class="manage-section"><div class="container manage-container">
    @if (session('status'))<div class="success-alert">{{ session('status') }}</div>@endif
    <a href="{{ route('portfolios.index') }}" class="back-link">← All portfolios</a>
    <div class="manage-heading"><div><div class="eyebrow"><span class="eyebrow-dot"></span> YOUR PORTFOLIO</div><h1>{{ $portfolio->full_name }}<br><em>is ready to go.</em></h1><p class="muted">Looking good. Share it when you’re ready, or make a few more edits.</p></div><a class="button button-dark" href="{{ route('portfolios.public', $portfolio->uuid) }}" target="_blank" rel="noopener">Open live page <span aria-hidden="true">↗</span></a></div>
    <div class="share-panel"><div><span class="share-label">YOUR PUBLIC LINK</span><a href="{{ route('portfolios.public', $portfolio->uuid) }}" target="_blank" rel="noopener">{{ route('portfolios.public', $portfolio->uuid) }}</a></div><a class="copy-link" href="{{ route('portfolios.public', $portfolio->uuid) }}" target="_blank" rel="noopener">Open link ↗</a></div>
    <div class="manage-grid">
        <div class="manage-preview">
            <div class="manage-preview-top"><span>LIVE PREVIEW</span><a href="{{ route('portfolios.public', $portfolio->uuid) }}" target="_blank" rel="noopener">Open full page ↗</a></div>
            <iframe src="{{ route('portfolios.public', $portfolio->uuid) }}" title="Portfolio preview for {{ $portfolio->full_name }}" loading="lazy"></iframe>
        </div>
        <aside class="manage-actions">
            <div class="action-card"><span class="action-icon">✎</span><h2>Keep it current</h2><p>Update your story, experience, and projects whenever life changes.</p><a class="text-link" href="{{ route('portfolios.edit', $portfolio) }}">Edit your details <span>↗</span></a></div>
            <div class="action-card"><span class="action-icon">◫</span><h2>Try another look</h2><p>Currently using the <strong>{{ ucfirst($portfolio->template) }}</strong> template.</p><a class="text-link" href="{{ route('portfolios.templates', $portfolio) }}">Change template <span>↗</span></a></div>
            <div class="action-card action-danger"><span class="action-icon">×</span><h2>Remove portfolio</h2><p>This permanently deletes your portfolio and its uploaded profile image.</p><form method="POST" action="{{ route('portfolios.destroy', $portfolio) }}" onsubmit="return confirm('Delete this portfolio permanently?')">@csrf @method('DELETE')<button class="text-link danger-link" type="submit">Delete portfolio <span>↗</span></button></form></div>
        </aside>
    </div>
</div></section>
@endsection
