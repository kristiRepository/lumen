<?php

namespace App\Listeners;


use App\Events\SignUpCustomerEvent;
use App\Mail\SendVerificationMailable;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailCustomerListener
{
    use ApiResponser;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Undocumented function
     *
     * @param SignUpAgencyEvent $event
     * @return void
     */
    public function handle(SignUpCustomerEvent $event)
    {
        $user=$event->getUser();
        Mail::to($user->email)->send(new SendVerificationMailable($user->v_key));

        

    }
}
