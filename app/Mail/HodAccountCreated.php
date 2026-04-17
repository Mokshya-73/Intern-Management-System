<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HodAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $regNo;
    public $email;
    public $password;

    public function __construct($name, $regNo, $email, $password)
    {
        $this->name = $name;
        $this->regNo = $regNo;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('HOD Account Created')
                    ->view('emails.hod_account_created');
    }
}

