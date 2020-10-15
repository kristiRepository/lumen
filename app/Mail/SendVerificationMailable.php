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
     private $id;
 
    public function __construct($v_key,$id)
    {
        $this->v_key=$v_key;
        $this->id=$id;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('mail.verification')->with('vkey',$this->v_key)->with('id',$this->id)
            ->subject('Verification Email');
    }
}
