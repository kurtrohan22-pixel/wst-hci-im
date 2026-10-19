<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_user_can_save_select_preview_edit_and_delete_a_portfolio(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('portfolios.index'))->assertOk();
        $this->get(route('portfolios.create'))->assertOk();

        $response = $this->post(route('portfolios.store'), [
            'full_name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'phone' => '555-0123',
            'address' => 'Portland, OR',
            'about' => 'Designer building thoughtful digital products.',
            'education_text' => "B.A. Design | Northstar University | 2020–2024",
            'skills_text' => "Product design\nResearch",
            'projects_text' => 'Northstar | Brand identity | https://example.com',
            'experience_text' => 'Product designer | Studio North | 2022–present',
            'social_links_text' => 'LinkedIn | https://linkedin.com/in/alex',
        ]);

        $portfolio = Portfolio::firstOrFail();
        $response->assertRedirect(route('portfolios.templates', $portfolio));
        $this->assertSame($user->id, $portfolio->user_id);
        $this->assertSame(['Product design', 'Research'], $portfolio->skills);
        $this->assertSame('simple', $portfolio->template);
        $this->get(route('portfolios.templates', $portfolio))->assertOk()->assertSee('Creative');
        $this->get(route('portfolios.show', $portfolio))->assertOk()->assertSee('YOUR PUBLIC LINK');
        $this->get(route('portfolios.edit', $portfolio))->assertOk()->assertSee('Full name');

        $this->actingAs($user)->put(route('portfolios.template', $portfolio), [
            'template' => 'modern',
        ])->assertRedirect(route('portfolios.show', $portfolio));

        $portfolio->refresh();
        $this->assertSame('modern', $portfolio->template);
        $this->get(route('portfolios.public', $portfolio->uuid))
            ->assertOk()
            ->assertSee('Alex Morgan')
            ->assertSee('Northstar');

        $this->actingAs($user)->put(route('portfolios.update', $portfolio), [
            'full_name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'about' => 'Updated biography',
            'skills_text' => 'Design systems',
        ])->assertRedirect(route('portfolios.show', $portfolio));
        $this->assertSame('Updated biography', $portfolio->fresh()->about);

        $this->actingAs($user)->delete(route('portfolios.destroy', $portfolio))
            ->assertRedirect(route('portfolios.index'));
        $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
    }

    public function test_users_cannot_manage_another_users_portfolio(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $portfolio = Portfolio::create([
            'user_id' => $owner->id,
            'full_name' => 'Private Portfolio',
            'email' => 'owner@example.com',
        ]);

        $this->actingAs($otherUser)
            ->get(route('portfolios.edit', $portfolio))
            ->assertNotFound();
    }

    public function test_each_of_the_three_portfolio_templates_renders(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::create([
            'user_id' => $user->id,
            'full_name' => 'Taylor Rivera',
            'email' => 'taylor@example.com',
        ]);

        foreach ([
            'simple' => 'simple-hero',
            'modern' => 'modern-hero',
            'creative' => 'creative-hero',
        ] as $template => $marker) {
            $portfolio->update(['template' => $template]);

            $this->get(route('portfolios.public', $portfolio->uuid))
                ->assertOk()
                ->assertSee($marker);
        }
    }

    public function test_a_guest_can_create_an_account(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Taylor Rivera',
            'email' => 'taylor@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ])->assertRedirect(route('portfolios.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'taylor@example.com']);
    }

    public function test_portfolio_links_must_use_http_or_https(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('portfolios.create'))
            ->post(route('portfolios.store'), [
                'full_name' => 'Alex Morgan',
                'email' => 'alex@example.com',
                'social_links_text' => 'Bad link | javascript:alert(1)',
            ])
            ->assertSessionHasErrors('social_links_text');
    }
}
