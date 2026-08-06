<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Token reset yang unik dan berbeda setiap request.
     * Di-generate otomatis oleh Laravel Password Broker.
     */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // URL reset unik berisi token random + email user
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password — HR Management System')
            ->view('emails.reset-password', [
                'resetUrl'  => $resetUrl,
                'userName'  => $notifiable->name,
                'expireMin' => config('auth.passwords.users.expire', 60),
            ]);
    }
}
