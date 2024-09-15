<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $data_email;
    public function __construct($data)
    {
        $this->data_email = is_array($data) ? $data : ['mensaje'=>'esto es nulo'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Acta soporte y tecnologia (TIMAC)',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'correo.envio_correos_view',
            with:[
                'nombre'=> strtoupper($this->data_email["nombre_encargado"]),
                'tipo_acta'=> strtoupper($this->data_email["tipo_acta"]),
                'nombre_gestor'=> $this->data_email["nombre_gestor"],
                'cargo_operacion'=> $this->data_email["cargo_operacion"],
                'op_solicitante'=> $this->data_email["op_solicitante"],
                'n_caso'=> $this->data_email["n_caso"],
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path($this->data_email['ruta_pdf'])),
        ];
    }
}
