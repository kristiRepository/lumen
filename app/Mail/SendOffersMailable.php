<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOffersMailable extends Mailable 
{
    

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $body;
    public function __construct($body)
    {
        $this->body=$body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.offers',['body',$this->body]);
    }
}