<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use App\Domain\Entities\Account;

class WelcomeAccount extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(private Account $account) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account created with suscess!')
            ->line("Hello " . Str::title($notifiable->name) . ",")
            ->line("We are happy to inform you that your account has been successfully created..")
            ->line("Now you can start using our services and enjoy all the benefits we offer.")
            ->line($this->account->getIsDefault() ? "Your account is the default account for transactions and access" : "Your account is not the default account for transactions and access")
            ->line("Your account has the value of: " . $this->account->getBalance()->format());
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['user_id' => $notifiable->id];
    }
}
