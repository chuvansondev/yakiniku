<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_pages_render(): void
    {
        $this->withoutVite();
        $this->get(route('login'))->assertOk()->assertSee(route('password.request'));
        $this->get(route('password.request'))->assertOk()->assertSee('name="email"', false);
        $user = User::factory()->customer()->create();
        $this->get(route('password.reset', ['token' => Password::createToken($user), 'email' => $user->email]))
            ->assertOk()->assertSee($user->email)->assertSee('name="password_confirmation"', false)
            ->assertHeader('Referrer-Policy', 'no-referrer');
    }

    public function test_reset_email_is_sent_with_a_working_link_and_requests_are_throttled(): void
    {
        Notification::fake();
        $user = User::factory()->customer()->create();
        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
            $token = basename(parse_url($url, PHP_URL_PATH));
            return $query['email'] === $user->email && Password::tokenExists($user, $token);
        });
        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');
        Notification::assertSentToTimes($user, ResetPasswordNotification::class, 1);
        $this->post(route('password.email'), ['email' => 'unknown@example.com'])->assertSessionHas('status');
        Notification::assertCount(1);
    }

    public function test_reset_changes_password_and_token_cannot_be_reused(): void
    {
        $user = User::factory()->customer()->create();
        $remember = $user->remember_token;
        $version = $user->session_version;
        $data = $this->resetData($user);
        $this->post(route('password.update'), $data)->assertRedirect(route('login'))->assertSessionHas('status');
        $user->refresh();
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertNotSame($remember, $user->remember_token);
        $this->assertEquals($version + 1, $user->session_version);
        $this->assertGuest();
        $this->post(route('password.update'), $data)->assertSessionHasErrors('email');
    }

    public function test_invalid_expired_and_wrong_account_tokens_are_rejected(): void
    {
        $user = User::factory()->customer()->create();
        $data = $this->resetData($user);
        $this->post(route('password.update'), array_replace($data, ['token' => 'invalid']))->assertSessionHasErrors('email');
        $other = User::factory()->customer()->create();
        $this->post(route('password.update'), array_replace($data, ['email' => $other->email]))->assertSessionHasErrors('email');
        $this->travel(61)->minutes();
        $this->post(route('password.update'), $data)->assertSessionHasErrors('email');
        $this->assertFalse(Hash::check($data['password'], $user->fresh()->password));
    }

    public function test_password_confirmation_and_length_are_required(): void
    {
        $user = User::factory()->customer()->create();
        $data = $this->resetData($user);
        $this->post(route('password.update'), array_replace($data, ['password_confirmation' => 'different']))->assertSessionHasErrors('password');
        $this->post(route('password.update'), array_replace($data, ['password' => 'short', 'password_confirmation' => 'short']))->assertSessionHasErrors('password');
        $this->assertTrue(Password::tokenExists($user, $data['token']));
    }

    public function test_opening_invalid_expired_or_used_links_offers_a_new_link(): void
    {
        $this->withoutVite();
        $user = User::factory()->customer()->create();
        $data = $this->resetData($user);
        $url = route('password.reset', ['token' => $data['token'], 'email' => $user->email]);
        foreach ([
            route('password.reset', ['token' => 'invalid', 'email' => $user->email]),
            route('password.reset', ['token' => $data['token']]),
            route('password.reset', ['token' => $data['token'], 'email' => ['invalid']]),
        ] as $invalidUrl) {
            $this->get($invalidUrl)->assertOk()->assertDontSee('name="password_confirmation"', false)
                ->assertSee(route('password.request'));
        }
        $this->post(route('password.update'), $data)->assertRedirect(route('login'));
        $this->get($url)->assertOk()->assertDontSee('name="password_confirmation"', false);
        $data = $this->resetData($user);
        $this->travel(61)->minutes();
        $this->get(route('password.reset', ['token' => $data['token'], 'email' => $user->email]))
            ->assertOk()->assertDontSee('name="password_confirmation"', false);
    }

    public function test_email_link_can_be_used_to_reset_and_sign_in(): void
    {
        $this->withoutVite();
        Notification::fake();
        $user = User::factory()->customer()->create();
        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');
        $notification = Notification::sent($user, ResetPasswordNotification::class)->first();
        $url = $notification->toMail($user)->actionUrl;
        $this->get($url)->assertOk()->assertSee('name="password_confirmation"', false);
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->post(route('password.update'), [
            'email' => $query['email'],
            'token' => basename(parse_url($url, PHP_URL_PATH)),
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertRedirect(route('login'));
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'new-password-123'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    private function resetData(User $user): array
    {
        return [
            'email' => $user->email,
            'token' => Password::createToken($user),
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ];
    }
}
