@extends('layouts.app')

@section('title', 'Choose a template')
@section('content')
<section class="template-section"><div class="container">
    <a href="{{ route('portfolios.show', $portfolio) }}" class="back-link">← Back to portfolio</a>
    <div class="template-heading"><div class="eyebrow"><span class="eyebrow-dot"></span> STEP 02 · PICK YOUR FEELING</div><h1>Same story.<br><em>Different energy.</em></h1><p class="muted">Choose a starting point. You can switch styles whenever you like.</p></div>
    <form method="POST" action="{{ route('portfolios.template', $portfolio) }}" class="template-grid">@csrf @method('PUT')
        @foreach ($templates as $key => $template)
            <label class="template-option">
                <input type="radio" name="template" value="{{ $key }}" {{ old('template', $portfolio->template) === $key ? 'checked' : '' }}>
                <span class="template-preview template-preview-{{ $key }}">
                    @if ($key === 'simple')
                        <span class="mini-simple"><i>{{ $portfolio->full_name }}</i><b>ABOUT</b><span></span><span></span><b>SELECTED WORK</b><span></span><span></span></span>
                    @elseif ($key === 'modern')
                        <span class="mini-modern"><i></i><b>{{ $portfolio->full_name }}</b><span>DESIGNER &amp; MAKER</span><span class="mini-modern-row"><i></i><i></i></span><span class="mini-modern-footer">01 &nbsp; 02 &nbsp; 03</span></span>
                    @else
                        <span class="mini-creative"><b>MAKE<br>GOOD<br><i>things.</i></b><span class="mini-creative-circle">✳</span><span class="mini-creative-footer">{{ $portfolio->full_name }}</span></span>
                    @endif
                    <span class="template-check">✓</span>
                </span>
                <span class="template-info"><span><b>{{ $template['name'] }}</b><span>{{ $template['description'] }}</span></span><span class="template-radio"></span></span>
            </label>
        @endforeach
        @error('template')<p class="field-error">{{ $message }}</p>@enderror
        <div class="template-actions"><span>Just right? You can preview before sharing.</span><button class="button button-dark" type="submit">Preview portfolio <span aria-hidden="true">↗</span></button></div>
    </form>
</div></section>
@endsection
