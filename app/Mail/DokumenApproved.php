<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DokumenApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaPengirim,
        public string $judulDokumen,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Dokumen Anda Telah Disetujui – SIPORA');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.dokumen-approved');
    }
}
