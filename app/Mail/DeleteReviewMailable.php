<?php

namespace App\Mail;


use Illuminate\Mail\Mailable;



class DeleteReviewMailable extends Mailable
{


    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $reason;

    public function __construct($reason)
    {
        $this->reason=$reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch($this->reason){

            case 'language':
                 return $this->view('mail.review.language')
                ->subject('Your review has been deleted');

            case 'private':
                return $this->view('mail.review.private')
                ->subject('Your review has been deleted');

            case 'blackmail':
                return $this->view('mail.review.blackmail')
                ->subject('Your review has been deleted');
            default:
                return $this->view('mail.review.default')
                ->subject('Your review has been deleted');
        }
       
    }
}
