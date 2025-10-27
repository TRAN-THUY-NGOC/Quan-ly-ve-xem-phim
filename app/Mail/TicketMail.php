<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct($order, $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('🎬 Vé xem phim #' . $this->order->id)
                    ->view('emails.ticket_mail')
                    ->attachData($this->pdf->output(), 'Ve-dien-tu.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
