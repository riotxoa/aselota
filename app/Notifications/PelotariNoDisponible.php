<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Pelotari;

class PelotariNoDisponible extends Notification implements ShouldQueue
{
    use Queueable;

    private $from;
    private $pelotari_id;
    private $alias;
    private $destinatarios;
    private $disponible;
    private $date_from;
    private $date_to;
    private $texto;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $notificacion )
    {
      $pelotari = Pelotari::find($notificacion->pelotari_id);

      $this->from = $notificacion->from;
      $this->pelotari_id = $notificacion->pelotari_id;
      $this->alias = $pelotari['alias'];
      $this->destinatarios = $notificacion->destinatarios;
      $this->disponible = $notificacion->disponible;
      $this->date_from = $notificacion->date_from;
      $this->date_to = $notificacion->date_to;
      $this->texto = $notificacion->texto;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if( $this->disponible ) {
          $subject = "Situación médica de $this->alias";
        } else {
          $subject = "$this->alias NO DISPONIBLE";
        }

        return (new MailMessage)
          ->subject($subject)
          ->from($this->from)
          ->view(
            'emailNotificacionMedica',
            [
              'alias' => $this->alias,
              'disponible' => $this->disponible,
              'date_from' => date('d/m/Y', strtotime($this->date_from)),
              'date_to' => date('d/m/Y', strtotime($this->date_to)),
              'texto' => $this->texto
            ]
          );
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
            'from' => $this->from,
            'pelotari_id' => $this->pelotari_id,
            'destinatarios' => $this->destinatarios,
            'disponible' => $this->disponible,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'texto' => $this->texto,
        ];
    }
}
