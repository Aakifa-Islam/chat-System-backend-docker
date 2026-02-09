<?php
namespace App\Notifications\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordOTPNotification extends Notification {
    public $otp;
    public function __construct($otp) { $this->otp = $otp; }
    public function via($notifiable) { return ['mail']; }
    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('Password Reset OTP')
            ->view('emails.password_otp', ['otp' => $this->otp, 'user' => $notifiable]);
    }
}