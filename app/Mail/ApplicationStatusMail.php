<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicantName;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($applicantName, $status)
    {
        $this->applicantName = $applicantName;
        $this->status = $status;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->status === 'Hired'
            ? '🎉 Congratulations! You’ve been hired!'
            : '⚡ Update on Your Application';

        return $this->subject($subject)
            ->view('emails.application-status');
    }
}
