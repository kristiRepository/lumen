<?php

namespace App\Jobs;

use App\Mail\DeleteReviewMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DeleteReviewJob extends Job implements ShouldQueue
{

    use SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $email;
    private $reason;

    public function __construct($email,$reason)
    {
        $this->email = $email;
        $this->reason = $reason;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

         Mail::to($this->email)->send(new DeleteReviewMailable($this->reason));
        
    }
}
