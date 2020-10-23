<?php

namespace App\Mail;


use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;



class CustomerPaymentMailable extends Mailable
{


    /**
     * Create a new message instance.
     *
     * @return void
     */

    
     private $agency;
     private $trip;
     private $invoice;

    public function __construct($agency,$trip,$invoice)
    {
       $this->agency=$agency;
       $this->trip=$trip;
       $this->invoice=$invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.payment.customer')->with('agency',$this->agency)->with('trip',$this->trip)
            ->subject('Successful payment')
            ->attach( Storage::url('app/invoices/invoice'.$this->invoice.'.pdf'));
       
    }
}
