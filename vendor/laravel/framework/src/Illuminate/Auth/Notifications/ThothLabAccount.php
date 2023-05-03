<?php

namespace Illuminate\Auth\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class ThothLabAccount extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your account on ThoTh Lab (www.thothlab.com) have been created for you.')
            ->line('Your ThoTh Lab username is:')
            ->line($notifiable['attributes']['email'])
            ->line('Please click on the button below to set password for your account.')
            ->action('Set Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('The link above will be vaild for 12 hours. If expired, please use https://www.thothlab.com/password/reset to create your password.');

    }
}
