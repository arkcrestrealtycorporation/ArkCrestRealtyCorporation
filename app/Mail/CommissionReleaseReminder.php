<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CommissionReleaseReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $releases;
    public string $releaseDate;

    public function __construct(Collection $releases, string $releaseDate)
    {
        $this->releases    = $releases;
        $this->releaseDate = $releaseDate;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Commission Release Reminder — ' . $this->releaseDate,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.commission-release-reminder',
            with: [
                'releases'    => $this->releases,
                'releaseDate' => $this->releaseDate,
            ],
        );
    }
}
