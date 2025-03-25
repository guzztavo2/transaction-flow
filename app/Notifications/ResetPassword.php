<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ResetPassword extends Notification
{
    private string $token;
    private int $maxHours = 2;

    /**
     * Create a new notification instance.
     */
    public function __construct($maxHours = 2)
    {
        $this->maxHours = $maxHours;
        $this->token = urlencode(hash('crc32', Str::random(5)));
    }

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
            ->subject('Redefinição de Senha')
            ->line('Recebemos uma solicitação de redefinição de senha para sua conta.')
            ->action('Redefinir Senha', url('/auth/change-password/' . $this->token . '?' . http_build_query(['email' => urlencode($notifiable->email)])))
            ->line('Caso não foi você, desconsidere essa mensagem!')
            ->line("O link de redefinição de senha expira em $this->maxHours horas.")
            ->line('Obrigado por usar nossa aplicação!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['user_id' => $notifiable->id, 'token' => $this->token];
    }
}
