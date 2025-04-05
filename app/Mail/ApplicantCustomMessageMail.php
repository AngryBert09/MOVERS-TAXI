<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantCustomMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicantName;
    public $subjectLine;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($applicantName, $subjectLine, $customMessage)
    {
        $this->applicantName = $applicantName;
        $this->subjectLine = $subjectLine;
        $this->customMessage = $customMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.custom-message');
    }
}
