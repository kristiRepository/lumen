<?php

namespace App\Listeners;


use App\Events\SignUpAgencyEvent;
use App\Mail\SendVerificationMailable;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailListener
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
    public function handle(SignUpAgencyEvent $event)
    {
        $user=$event->getUser();
        Mail::to($user->email)->send(new SendVerificationMailable($user->v_key,$user->id));

        

    }
}
