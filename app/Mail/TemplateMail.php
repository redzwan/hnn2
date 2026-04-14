<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $renderedSubject;

    public string $renderedBody;

    /**
     * @param  array<string, string>  $data
     */
    public function __construct(
        public readonly EmailTemplate $template,
        public readonly array $data = []
    ) {
        $this->renderedSubject = $template->renderSubject($data);
        $this->renderedBody = $template->renderBody($data);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->renderedSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.template');
    }
}
