<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicantName;
    public $interviewDate;
    public $interviewTime;

    /**
     * Create a new message instance.
     */
    public function __construct($applicantName, $interviewDate, $interviewTime)
    {
        $this->applicantName = $applicantName;
        $this->interviewDate = $interviewDate;
        $this->interviewTime = $interviewTime;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Interview Scheduled Successfully')
                    ->view('emails.interview-scheduled');
    }
}
