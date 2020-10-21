<?php

namespace App\Services;

use App\Jobs\DeleteReviewJob;
use App\Services\ServiceInterface;


class AdminService implements ServiceInterface
{


    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function sendMail($email, $reason)
    {
        dispatch(new DeleteReviewJob($email,$reason));
    }
}
