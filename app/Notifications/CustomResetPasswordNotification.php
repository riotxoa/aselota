<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      if (static::$toMailCallback) {
          return call_user_func(static::$toMailCallback, $notifiable, $this->token);
      }

      return (new MailMessage)
          ->greeting('Hola,')
          ->subject('Reiniciar Contraseña')
          ->line('Ha recibido este mensaje porque ha solicitado un reinicio de contraseña para su cuenta en la aplicación de Baiko Pilota. Clique sobre el botón que aparece a continuación y podrá establecer una nueva contraseña.')
          ->action('Reiniciar Contraseña', url(config('app.url').route('password.reset', $this->token, false)))
          ->line('Si no ha solicitado reiniciar su contraseña, ignore este mensaje.')
          ->salutation('Un saludo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
