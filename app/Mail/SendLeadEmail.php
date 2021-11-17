<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $leadEmail;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($leadEmail, $settings)
    {
        $this->leadEmail = $leadEmail;
        $this->settings  = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->settings['company_email'], $this->settings['company_email_from_name'])->markdown('email.lead_email')->subject($this->leadEmail->subject)->with(
            [
                'leadEmail' => $this->leadEmail,
                'mail_header' => $this->settings['company_name'],
            ]
        );
    }
}
