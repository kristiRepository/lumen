<?php

namespace App\Mail;


use Illuminate\Mail\Mailable;



class SendVerificationMailable extends Mailable
{


    /**
     * Create a new message instance.
     *
     * @return void
     */

     private $v_key;
     
 
    public function __construct($v_key)
    {
        $this->v_key=$v_key;
      
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('mail.verification')->with('vkey',$this->v_key)
            ->subject('Verification Email');
    }
}
