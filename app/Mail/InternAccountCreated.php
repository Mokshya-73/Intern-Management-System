<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $regNo, $initialPassword, $token, $email;

    public function __construct($regNo, $initialPassword, $token, $email)
    {
        $this->regNo = $regNo;
        $this->initialPassword = $initialPassword;
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Your Internship Portal Credentials')
            ->view('emails.intern_credentials');
    }
}
