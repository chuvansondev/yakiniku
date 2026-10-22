<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    private function account(): User
    {
        return User::factory()->customer()->create(['password' => 'current-password'])->refresh();
    }

    private function credentials(array $overrides = []): array
    {
        return array_replace([
            'current_password' => 'current-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ], $overrides);
    }

    public function test_guests_cannot_view_or_submit_change_password(): void
    {
        $this->get(route('password.change'))->assertRedirect(route('login'));
        $this->post(route('password.change.update'), $this->credentials())->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_form_and_change_password(): void
    {
        $this->withoutVite();
        $user = $this->account();
        $token = Password::createToken($user);
        $remember = $user->remember_token;
        $version = $user->session_version;
        $this->actingAs($user)->withSession(['auth_session_version' => $version]);
        $this->get(route('password.change'))->assertOk()->assertSee('name="current_password"', false);
        $this->post(route('password.change.update'), $this->credentials())
            ->assertRedirect(route('password.change'))->assertSessionHas('status')
            ->assertSessionHas('auth_session_version', $version + 1);
        $user->refresh();
        $this->assertTrue(Hash::check('new-password-123', $user->password));
        $this->assertFalse(Hash::check('current-password', $user->password));
        $this->assertNotSame($remember, $user->remember_token);
        $this->assertFalse(Password::tokenExists($user, $token));
        $this->assertAuthenticatedAs($user);
        $this->get(route('password.change'))->assertOk();
        $this->withSession(['auth_session_version' => $version])->get(route('password.change'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_wrong_current_password_and_invalid_new_password_are_rejected(): void
    {
        $user = $this->account();
        $this->actingAs($user);
        foreach ([
            ['current_password' => 'incorrect'],
            ['password_confirmation' => 'different'],
            ['password' => 'short', 'password_confirmation' => 'short'],
        ] as $overrides) {
            $this->post(route('password.change.update'), $this->credentials($overrides))
                ->assertSessionHasErrors(isset($overrides['current_password']) ? 'current_password' : 'password');
        }
        $this->assertTrue(Hash::check('current-password', $user->fresh()->password));
    }

    public function test_locked_account_cannot_change_password(): void
    {
        $user = $this->account();
        $user->update(['is_locked' => true]);
        $this->actingAs($user)->post(route('password.change.update'), $this->credentials())
            ->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('current-password', $user->fresh()->password));
    }
}
