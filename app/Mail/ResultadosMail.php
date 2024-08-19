<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResultadosMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evaluacion = "";
    public $url_pdf = "";
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evaluacion, $url_pdf)
    {
        $this->evaluacion = $evaluacion;
        $this->url_pdf = $url_pdf;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Resultados',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'Mail.resultados',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    
    public function attachments()
    {
        return [
            Attachment::fromStorage($this->url_pdf),
        ];
        // // Ruta del archivo PDF en el almacenamiento
        // $pdfPath = storage_path('app/' . $this->url_pdf);

        // return [
        //     [
        //         'file' => $pdfPath,
        //         'options' => [
        //             'as' => 'Resultados.pdf', // Nombre del archivo adjunto
        //             'mime' => 'application/pdf',
        //         ],
        //     ],
        // ];
    }

    
}
