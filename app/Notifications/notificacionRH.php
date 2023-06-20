<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificacionRH extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $RH;
    public $dueño;
    public $nombre_sala;
    public $ubicacion;
    public $hora_inicio;
    public $hora_fin;
    public $material;
    public $cantidadSillas;
    public $description;
    public function __construct($RH,$dueño,$nombre_sala, $ubicacion, $hora_inicio,$hora_fin,$material,$cantidadSillas,$description)
    {
        $this->RH=$RH;
        $this->dueño=$dueño;
        $this->nombre_sala=$nombre_sala;
        $this->ubicacion=$ubicacion;
        $this->hora_inicio=$hora_inicio;
        $this->hora_fin=$hora_fin;
        $this->material=$material;
        $this->cantidadSillas=$cantidadSillas;
        $this->description=$description;
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
        return (new MailMessage)
                    ->markdown('mail.reservation.MaterialRH',[
                        'RH'=>$this->RH,
                        'dueño'=>$this->dueño,
                        'nombre_sala'=>$this->nombre_sala,
                        'ubicacion'=>$this->ubicacion,
                        'hora_inicio'=>$this->hora_inicio,
                        'hora_fin'=>$this->hora_fin,
                        'material'=>$this->material,
                        'cantidadSillas'=>$this->cantidadSillas,
                        'description'=>$this->description,
        ])
        ->subject('HAN SOLICITADO MATERIAL (SILLAS)')
        ->from('admin@intranet.promolife.lat', 'Intranet Corporativa BH - PL');
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
