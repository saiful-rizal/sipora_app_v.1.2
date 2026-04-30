<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nama,
        public string $alasan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Penolakan Akun SIPORA'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-rejected'
        );
    }
}