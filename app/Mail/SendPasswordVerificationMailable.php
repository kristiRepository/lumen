<?php

namespace App\Mail;


use Illuminate\Mail\Mailable;



class SendPasswordVerificationMailable extends Mailable
{


    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $body;
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.password-verify')->with('vkey',$this->body)
            ->subject('Verify Password');
    }
}
