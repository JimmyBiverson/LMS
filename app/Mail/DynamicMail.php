<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $bodyContent;

    public function __construct(string $subject, string $body)
    {
        $this->subjectText = $subject;
        $this->bodyContent = $body;
    }

    public function build(): self
    {
        return $this->subject($this->subjectText)
            ->html($this->bodyContent);
    }
}
}
