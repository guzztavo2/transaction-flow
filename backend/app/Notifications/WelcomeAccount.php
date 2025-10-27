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
            ->subject('Criação de Conta')
            ->line("Olá " . Str::title($notifiable->name) . ",")
            ->line("Estamos felizes em informar que sua conta foi criada com sucesso.")
            ->line("Agora você pode começar a usar nossos serviços e aproveitar todos os benefícios que oferecemos.")
            ->line($this->account->getIsDefault() ? "Sua conta é a padrão de transações e de seu acesso" : "Sua conta não é a padrão de transações e de seu acesso")
            ->line("Sua conta tem o valor de: " . $this->account->getBalance()->format())
            ->line("Sua conta foi criada com sucesso!");
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
