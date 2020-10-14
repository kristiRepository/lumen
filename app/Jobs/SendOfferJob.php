<?php

namespace App\Jobs;

use App\Mail\SendOffersMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOfferJob extends Job implements ShouldQueue
{

    use SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        foreach ($this->data['customers'] as $customer) {
            Mail::to($customer)->send(new SendOffersMailable($this->data['body']));
        }
    }
}
