<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_verification_and_refresh_cannot_bypass_it(): void
    {
        Notification::fake();
        $this->withoutVite();

        $this->withSession(['url.intended' => route('home')])->post(route('register.store'), [
            'name' => 'New customer',
            'email' => 'unverified@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'unverified@example.com')->firstOrFail();
        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmail::class);

        foreach (['home', 'home', 'booking.mine', 'booking.create', 'password.change'] as $route) {
            $this->get(route($route))->assertRedirect(route('verification.notice'));
        }
        $this->post(route('booking.store'), [])->assertRedirect(route('verification.notice'));
        $this->getJson(route('booking.availability'))->assertForbidden();
        $this->get(route('verification.notice'))->assertOk();
        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_unverified_login_goes_to_verification(): void
    {
        $user = User::factory()->customer()->unverified()->create(['password' => 'password123']);

        $this->post(route('login.store'), [
            'email' => $user->email, 'password' => 'password123', 'remember' => true,
        ])->assertRedirect(route('verification.notice'));
        $this->get(route('home'))->assertRedirect(route('verification.notice'));
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_valid_signed_link_verifies_email_and_unlocks_home(): void
    {
        $this->withoutVite();
        $user = User::factory()->customer()->unverified()->create();

        $this->actingAs($user)->get($this->verificationUrl($user))->assertRedirect(route('booking.mine'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->get(route('home'))->assertOk();
    }

    public function test_unsigned_expired_tampered_and_other_users_links_are_rejected(): void
    {
        $user = User::factory()->customer()->unverified()->create();
        $other = User::factory()->customer()->unverified()->create();
        $parameters = ['id' => $user->id, 'hash' => sha1($user->email)];
        $urls = [
            route('verification.verify', $parameters),
            URL::temporarySignedRoute('verification.verify', now()->subMinute(), $parameters),
            $this->verificationUrl($user).'&tampered=1',
            $this->verificationUrl($other),
            URL::temporarySignedRoute('verification.verify', now()->addHour(), [
                'id' => $user->id, 'hash' => sha1('wrong@example.com'),
            ]),
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertForbidden();
            $this->assertFalse($user->fresh()->hasVerifiedEmail());
        }
        $this->assertFalse($other->fresh()->hasVerifiedEmail());
    }

    public function test_resend_sends_notification_without_verifying_account_and_is_throttled(): void
    {
        Notification::fake();
        $user = User::factory()->customer()->unverified()->create();
        $this->actingAs($user);

        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->from(route('verification.notice'))->post(route('verification.send'))
                ->assertRedirect(route('verification.notice'));
        }
        $this->post(route('verification.send'))->assertStatus(429);
        Notification::assertSentToTimes($user, VerifyEmail::class, 6);
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    private function verificationUrl(User $user): string
    {
        return URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $user->id, 'hash' => sha1($user->email),
        ]);
    }
}
