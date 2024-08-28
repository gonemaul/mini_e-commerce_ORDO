<?php

namespace App\Notifications\Auth;

use App\Models\EmailVerify;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerifyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->getURL($notifiable);
        return (new MailMessage)
                        ->subject(Lang::get('Verify Email Address'))
                        ->line(Lang::get('Please click the button below to verify your email address.'))
                        ->action(Lang::get('Verify Email Address'), $url)
                        ->line(Lang::get('This verification token will expire in 10 minutes.'))
                        ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function getURL($notifiable){
        $token =  Str::random(10) . sha1($notifiable->email);

        EmailVerify::updateOrCreate([
            'email' => $notifiable->email,
        ],[
            'token' => $token
        ]);
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->id,
                'hash' => $token,
            ]
            );
    }
}