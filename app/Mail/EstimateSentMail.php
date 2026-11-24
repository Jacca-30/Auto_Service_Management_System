<?php

namespace App\Mail;

use App\Models\Estimate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstimateSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Estimate $estimate,
        public string $approvalUrl
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jūsų automobilio remonto sąmata'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.estimates.sent'
        );
    }
}
