<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    private const TEMPLATES = [
        'simple' => ['name' => 'Simple', 'description' => 'Quiet confidence, with a focus on your story.'],
        'modern' => ['name' => 'Modern', 'description' => 'A polished, card-based layout with strong visual hierarchy.'],
        'creative' => ['name' => 'Creative', 'description' => 'An expressive editorial layout for bold ideas.'],
    ];

    public function index(Request $request): View
    {
        $portfolios = $request->user()->portfolios()->latest()->get();

        return view('portfolios.index', compact('portfolios'));
    }

    public function create(): View
    {
        return view('portfolios.form', ['portfolio' => new Portfolio(), 'editing' => false]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['user_id'] = $request->user()->id;
        $data['profile_image'] = $request->file('profile_image')?->store('profiles', 'public');
        $portfolio = Portfolio::create($data);

        return redirect()->route('portfolios.templates', $portfolio)->with('status', 'Your portfolio is saved.');
    }

    public function edit(Request $request, Portfolio $portfolio): View
    {
        $portfolio = $this->ownedPortfolio($request, $portfolio);

        return view('portfolios.form', ['portfolio' => $portfolio, 'editing' => true]);
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $portfolio = $this->ownedPortfolio($request, $portfolio);
        $data = $this->validatedData($request);

        if ($request->hasFile('profile_image')) {
            if ($portfolio->profile_image) {
                Storage::disk('public')->delete($portfolio->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $portfolio->update($data);

        return redirect()->route('portfolios.show', $portfolio)->with('status', 'Your changes are saved.');
    }

    public function show(Request $request, Portfolio $portfolio): View
    {
        $portfolio = $this->ownedPortfolio($request, $portfolio);

        return view('portfolios.show', compact('portfolio'));
    }

    public function templates(Request $request, Portfolio $portfolio): View
    {
        $portfolio = $this->ownedPortfolio($request, $portfolio);
        $templates = self::TEMPLATES;

        return view('portfolios.templates', compact('portfolio', 'templates'));
    }

    public function selectTemplate(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $portfolio = $this->ownedPortfolio($request, $portfolio);
        $validated = $request->validate([
            'template' => ['required', Rule::in(array_keys(self::TEMPLATES))],
        ]);
        $portfolio->update($validated);

        return redirect()->route('portfolios.show', $portfolio)->with('status', 'Template updated.');
    }

    public function destroy(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $portfolio = $this->ownedPortfolio($request, $portfolio);

        if ($portfolio->profile_image) {
            Storage::disk('public')->delete($portfolio->profile_image);
        }
        $portfolio->delete();

        return redirect()->route('portfolios.index')->with('status', 'Portfolio deleted.');
    }

    public function publicShow(string $uuid): View
    {
        $portfolio = Portfolio::where('uuid', $uuid)->firstOrFail();

        return view('portfolios.public', compact('portfolio'));
    }

    private function ownedPortfolio(Request $request, Portfolio $portfolio): Portfolio
    {
        abort_unless($portfolio->user_id === $request->user()->id, 404);

        return $portfolio;
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:3000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'education_text' => ['nullable', 'string', 'max:5000'],
            'skills_text' => ['nullable', 'string', 'max:3000'],
            'projects_text' => ['nullable', 'string', 'max:7000'],
            'experience_text' => ['nullable', 'string', 'max:7000'],
            'social_links_text' => ['nullable', 'string', 'max:3000'],
        ]);

        $this->validateLinkLines($validated['projects_text'] ?? '', 2, 'projects_text');
        $this->validateLinkLines($validated['social_links_text'] ?? '', 1, 'social_links_text');

        return [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'about' => $validated['about'] ?? null,
            'education' => $this->lines($validated['education_text'] ?? ''),
            'skills' => $this->lines($validated['skills_text'] ?? ''),
            'projects' => $this->lines($validated['projects_text'] ?? ''),
            'experience' => $this->lines($validated['experience_text'] ?? ''),
            'social_links' => $this->lines($validated['social_links_text'] ?? ''),
        ];
    }

    private function validateLinkLines(string $value, int $urlIndex, string $field): void
    {
        foreach ($this->lines($value) as $line) {
            $parts = array_map('trim', explode('|', $line));
            $url = $parts[$urlIndex] ?? '';
            $scheme = parse_url($url, PHP_URL_SCHEME);

            if ($url !== '' && (! filter_var($url, FILTER_VALIDATE_URL) || ! in_array($scheme, ['http', 'https'], true))) {
                throw ValidationException::withMessages([
                    $field => 'Portfolio links must use a valid http or https URL.',
                ]);
            }
        }
    }

    private function lines(string $value): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: []),
            fn (string $line) => $line !== ''
        ));
    }
}
