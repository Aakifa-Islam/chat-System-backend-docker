<?php

namespace App\Notifications\Auth;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

   public function toMail($notifiable)
{
    $url = url("/api/verify/{$this->user->id}/{$this->token}");

    return (new MailMessage)
        ->subject('Verify Your Account')
        ->view('emails.verify', [ // Ye line folder.file_name ko match karni chahiye
            'user' => $this->user,
            'url'  => $url
        ]);
}
}