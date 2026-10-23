<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_login_returns_home_even_with_a_pending_booking_redirect(): void
    {
        $user = User::factory()->customer()->create(['password' => 'password']);

        $this->withSession(['url.intended' => route('booking.mine')])
            ->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('home'))
            ->assertSessionMissing('url.intended');

        $this->assertAuthenticatedAs($user);
    }
}
