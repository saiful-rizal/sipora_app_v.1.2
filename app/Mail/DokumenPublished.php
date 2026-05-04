<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DokumenPublished extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $namaPengirim,
        public readonly string $judulDokumen,
        public readonly string $nomorSurat,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Dokumen Anda Telah Dipublikasi — {$this->nomorSurat}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dokumen_published',
            with: [
                'namaPengirim' => $this->namaPengirim,
                'judulDokumen' => $this->judulDokumen,
                'nomorSurat'   => $this->nomorSurat,
            ],
        );
    }
}
