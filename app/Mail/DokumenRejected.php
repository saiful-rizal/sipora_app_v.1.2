<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DokumenRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string  $namaPengirim,
        public string  $judulDokumen,
        public string  $alasanReject,
        public ?string $filePath = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Dokumen Anda Ditolak – SIPORA');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.dokumen-rejected');
    }

    public function attachments(): array
    {
        if ($this->filePath && file_exists($this->filePath)) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($this->filePath)
                    ->as(basename($this->filePath)),
            ];
        }
        return [];
    }
}
