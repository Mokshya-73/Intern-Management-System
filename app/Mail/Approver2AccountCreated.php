<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Approver2AccountCreated extends Mailable
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
        return $this->subject('Approver 2 Account Created')
                    ->view('emails.approver2_account_created');
    }
}
