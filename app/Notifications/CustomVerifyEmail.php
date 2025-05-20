<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
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
        // URL personalizada para redirigir al hacer clic en el enlace
        $verificationUrl = 'https://figuribet.dawrodrigo.duckdns.org/login';

        //Mensaje
        return (new MailMessage)
            ->subject('¡Confirma tu correo en FiguriBET!')
            ->greeting('Hola ' . $notifiable->name . ' 👋')
            ->line('Gracias por registrarte. Antes de comenzar, necesitamos que confirmes tu correo electrónico.')
            ->action('Verificar mi correo', $verificationUrl)
            ->line('Si no creaste esta cuenta, puedes ignorar este mensaje.');
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
}
