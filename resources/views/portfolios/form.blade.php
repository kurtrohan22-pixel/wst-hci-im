@extends('layouts.app')

@section('title', $editing ? 'Edit portfolio' : 'Create a portfolio')
@section('content')
<section class="form-page"><div class="container form-container">
    <a href="{{ $editing ? route('portfolios.show', $portfolio) : route('portfolios.index') }}" class="back-link">← Back to {{ $editing ? 'portfolio' : 'dashboard' }}</a>
    <div class="form-heading"><div class="eyebrow"><span class="eyebrow-dot"></span> {{ $editing ? 'MAKE IT YOURS' : 'STEP 01 · YOUR STORY' }}</div><h1>{{ $editing ? 'A few things<br><em>have changed.</em>' : 'Start with<br><em>your story.</em>' }}</h1><p class="muted">Share as much or as little as you like. You can always come back and make it better.</p></div>
    @if ($errors->any())<div class="form-alert">Please check the highlighted fields and try again.</div>@endif
    <form method="POST" action="{{ $editing ? route('portfolios.update', $portfolio) : route('portfolios.store') }}" enctype="multipart/form-data" class="portfolio-form">
        @csrf
        @if ($editing)@method('PUT')@endif
        <section class="form-panel"><div class="panel-heading"><span class="panel-number">01</span><div><h2>The essentials</h2><p>How people can find and get to know you.</p></div></div>
            <div class="fields-grid">
                <label class="field-wide">Full name <span class="required-mark">*</span><input name="full_name" value="{{ old('full_name', $portfolio->full_name) }}" required maxlength="120" placeholder="e.g. Alex Morgan">@error('full_name')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label>Email address <span class="required-mark">*</span><input type="email" name="email" value="{{ old('email', $portfolio->email) }}" required placeholder="alex@example.com">@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label>Contact number<input name="phone" value="{{ old('phone', $portfolio->phone) }}" placeholder="+1 (555) 000-0000"></label>
                <label class="field-wide">Address<input name="address" value="{{ old('address', $portfolio->address) }}" placeholder="City, country"></label>
                <label class="field-wide">Profile picture<input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp"><small class="field-hint">JPG, PNG or WebP, up to 5 MB. Leave empty to keep the current photo.</small>@error('profile_image')<small class="field-error">{{ $message }}</small>@enderror</label>
                @if ($editing && $portfolio->profile_image)<div class="current-photo"><img src="{{ asset('storage/'.$portfolio->profile_image) }}" alt="Current profile picture"><span>Current profile picture</span></div>@endif
                <label class="field-wide">About me<textarea name="about" rows="5" maxlength="3000" placeholder="A few sentences about what you do, what you care about, and where you’re headed.">{{ old('about', $portfolio->about) }}</textarea>@error('about')<small class="field-error">{{ $message }}</small>@enderror</label>
            </div>
        </section>
        <section class="form-panel"><div class="panel-heading"><span class="panel-number">02</span><div><h2>Your experience</h2><p>One item per line. You can use “|” to separate details.</p></div></div>
            <div class="fields-grid">
                <label>Education<textarea name="education_text" rows="4" placeholder="B.A. Design | Northstar University | 2020–2024">{{ old('education_text', implode("\n", $portfolio->education ?? [])) }}</textarea><small class="field-hint">Degree | School | Dates</small></label>
                <label>Work experience<textarea name="experience_text" rows="4" placeholder="Product designer | Studio North | 2022–present">{{ old('experience_text', implode("\n", $portfolio->experience ?? [])) }}</textarea><small class="field-hint">Role | Company | Dates</small></label>
                <label>Skills<textarea name="skills_text" rows="4" placeholder="Product design&#10;Figma&#10;Research">{{ old('skills_text', implode("\n", $portfolio->skills ?? [])) }}</textarea><small class="field-hint">One skill per line.</small></label>
                <label>Projects<textarea name="projects_text" rows="4" placeholder="Northstar identity | Brand system for a new studio | https://example.com">{{ old('projects_text', implode("\n", $portfolio->projects ?? [])) }}</textarea><small class="field-hint">Project | What you did | Link</small></label>
                <label class="field-wide">Social media &amp; website links<textarea name="social_links_text" rows="3" placeholder="LinkedIn | https://linkedin.com/in/you&#10;Website | https://your-site.com">{{ old('social_links_text', implode("\n", $portfolio->social_links ?? [])) }}</textarea><small class="field-hint">Platform | URL</small></label>
            </div>
        </section>
        <div class="form-actions"><span><span class="required-mark">*</span> Required fields</span><button class="button button-dark" type="submit">{{ $editing ? 'Save your changes' : 'Save &amp; choose a style' }} <span aria-hidden="true">↗</span></button></div>
    </form>
</div></section>
@endsection
