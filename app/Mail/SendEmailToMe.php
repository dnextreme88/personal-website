<?php

namespace App\Mail;

use App\Models\ContactMe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailToMe extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected ContactMe $contact_me;

    public function __construct(ContactMe $contact_me)
    {
        $this->contact_me = $contact_me;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->contact_me->email, $this->contact_me->first_name. ' ' .$this->contact_me->last_name),
            subject: 'I have just filled up your form'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.send-email-to-me',
            with: ['contact_me' => $this->contact_me]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
