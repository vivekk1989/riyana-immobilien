<?php

namespace App\Mail;

use App\Models\NebenkostenPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NebenkostenPublished extends Mailable
{
    use Queueable, SerializesModels;

    public $period;

    /**
     * Create a new message instance.
     */
    public function __construct(NebenkostenPeriod $period)
    {
        $this->period = $period;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nebenkostenabrechnung ' . $this->period->year,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nebenkosten_published', // We need to create this view
            with: [
                'year' => $this->period->year,
                'unit' => $this->period->unit,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->period->pdf_path && Storage::disk('public')->exists($this->period->pdf_path)) {
            return [
                Attachment::fromStorageDisk('public', $this->period->pdf_path)
                    ->as('Nebenkostenabrechnung_' . $this->period->year . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}
