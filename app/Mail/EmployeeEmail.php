<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $sender;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $sender = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.employee')
                    ->with([
                        'body' => $this->body,
                        'sender' => $this->sender,
                    ]);
    }
}
