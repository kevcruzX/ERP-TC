<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDealEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $dealEmail;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dealEmail, $settings)
    {
        $this->dealEmail = $dealEmail;
        $this->settings  = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->settings['company_email'], $this->settings['company_email_from_name'])->markdown('email.deal_email')->subject($this->dealEmail->subject)->with(
            [
                'dealEmail' => $this->dealEmail,
                'mail_header' => $this->settings['company_name'],
            ]
        );
    }
}
