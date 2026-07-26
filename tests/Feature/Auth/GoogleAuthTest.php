<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Google');
    }

    public function test_google_redirects_correctly(): void
    {
        $response = $this->get(route('auth.google'));
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_google_callback_logs_in_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'google_id' => null,
        ]);

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-123');
        $abstractUser->shouldReceive('getEmail')->andReturn('john@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('John Doe');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/john');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        
        $this->assertSame('google-id-123', $user->refresh()->google_id);
        $this->assertSame('https://avatar.url/john', $user->avatar_url);
    }

    public function test_google_callback_creates_and_logs_in_new_user(): void
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-456');
        $abstractUser->shouldReceive('getEmail')->andReturn('newuser@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('New User');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/newuser');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        
        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-id-456', $user->google_id);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
