<?php

namespace App\Mail;


use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

class AgencyPaymentMailable extends Mailable
{


    /**
     * Create a new message instance.
     *
     * @return void
     */

     private $trip;
     private $customer;
     private $invoice;
    

    public function __construct($customer,$trip,$invoice)
    {
        
        $this->customer=$customer;
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

       
        return $this->view('mail.payment.agency')->with('customer',$this->customer)->with('trip',$this->trip)
        ->subject('Successful payment');
        // ->attach();
       
    }
}
