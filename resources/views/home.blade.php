@extends('layouts.app')

@section('title', 'Your work, beautifully told')

@section('content')
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow"><span class="eyebrow-dot"></span> YOUR WORK, IN A BETTER LIGHT</div>
            <h1>A portfolio that<br>feels like <em>you.</em></h1>
            <p class="hero-lede">Bring your experience, ideas, and best work together. Build a thoughtful portfolio in minutes — no design skills required.</p>
            <div class="hero-actions">
                <a class="button button-dark" href="{{ route(auth()->check() ? 'portfolios.create' : 'register') }}">Create your portfolio <span aria-hidden="true">↗</span></a>
                <a class="text-link" href="#how-it-works">See how it works <span aria-hidden="true">↓</span></a>
            </div>
            <div class="hero-note"><span class="avatar-stack"><i>A</i><i>M</i><i>J</i></span><span>Made for the work you’re proud of.</span></div>
        </div>
        <div class="hero-art" aria-label="Portfolio preview illustration">
            <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
            <div class="floating-tag tag-top">YOUR NEXT CHAPTER <span>↗</span></div>
            <div class="preview-card">
                <div class="preview-topline"><span class="preview-dot"></span><span class="preview-dot"></span><span class="preview-dot"></span><span class="preview-label">PERSONAL PORTFOLIO</span></div>
                <div class="preview-profile"><div class="preview-portrait">JD</div><div><div class="preview-name">Jordan Davis</div><div class="preview-role">Designer &amp; creative thinker</div></div></div>
                <div class="preview-rule"></div>
                <div class="preview-kicker">A LITTLE ABOUT ME</div>
                <div class="preview-lines"><i></i><i></i><i></i></div>
                <div class="preview-kicker preview-work-label">SELECTED WORK</div>
                <div class="preview-projects"><div class="preview-project"><span>01</span><b>Northstar identity</b><i>↗</i></div><div class="preview-project"><span>02</span><b>Fieldnotes mobile</b><i>↗</i></div></div>
            </div>
            <div class="floating-tag tag-bottom"><span class="tag-spark">✳</span> A little more you.</div>
            <div class="art-caption">GOOD WORK DESERVES A GOOD HOME.</div>
        </div>
    </div>
</section>

<section class="trust-strip"><div class="container trust-inner"><span>ONE PROFILE</span><i></i><span>THREE DISTINCT STYLES</span><i></i><span>YOUR OWN PUBLIC LINK</span><i></i><span>READY WHEN YOU ARE</span></div></section>

<section class="steps-section" id="how-it-works">
    <div class="container">
        <div class="section-heading"><div><div class="eyebrow">A SIMPLE START</div><h2>From blank page<br>to <em>that’s me.</em></h2></div><p>Everything you need to turn your experience into a portfolio worth sharing.</p></div>
        <div class="steps-grid">
            <article class="step-card"><span class="step-number">01</span><div class="step-icon">✳</div><h3>Tell your story</h3><p>Add your details, experience, projects, and the things you’re proud of.</p><span class="step-arrow">↗</span></article>
            <article class="step-card"><span class="step-number">02</span><div class="step-icon">◫</div><h3>Find your style</h3><p>Choose from three considered designs. Your content stays the same; the mood is yours.</p><span class="step-arrow">↗</span></article>
            <article class="step-card"><span class="step-number">03</span><div class="step-icon">↗</div><h3>Put it out there</h3><p>Preview your finished page and share its public link with the world.</p><span class="step-arrow">↗</span></article>
        </div>
    </div>
</section>

<section class="closing-section"><div class="container closing-inner"><div class="eyebrow"><span class="eyebrow-dot"></span> IT STARTS WITH YOU</div><h2>Your work has a story.<br><em>Let’s tell it well.</em></h2><a class="button button-light" href="{{ route(auth()->check() ? 'portfolios.create' : 'register') }}">Make your portfolio <span aria-hidden="true">↗</span></a></div></section>
@endsection
