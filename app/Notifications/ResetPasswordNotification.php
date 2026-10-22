<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isEnglish = app()->getLocale() === 'en';
        $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject($isEnglish ? 'Reset your Yakiniku King password' : 'Đặt lại mật khẩu Yakiniku King')
            ->greeting(($isEnglish ? 'Hello ' : 'Xin chào ').$notifiable->name.'!')
            ->line($isEnglish ? 'We received a password reset request for your account.' : 'Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.')
            ->action($isEnglish ? 'Reset password' : 'Đặt lại mật khẩu', $url)
            ->line($isEnglish ? "This link expires in {$minutes} minutes. If you did not request a password reset, ignore this email." : "Liên kết có hiệu lực trong {$minutes} phút. Nếu bạn không yêu cầu, hãy bỏ qua email này.");
    }
}
