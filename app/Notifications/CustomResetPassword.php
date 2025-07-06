<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CustomResetPassword extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));

        return (new MailMessage)
            ->subject('Permintaan Reset Password Arsip Digital')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mereset password akun Anda.')
            ->action('Reset Password Sekarang', $resetUrl)
            ->line('Link ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
            ->salutation('Salam hangat, Tim Arsip Digital');
    }
}
