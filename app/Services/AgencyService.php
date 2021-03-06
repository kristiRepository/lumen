<?php

namespace App\Services;

use App\Jobs\SendOfferJob;
use App\Services\ServiceInterface;


class AgencyService implements ServiceInterface
{


    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function sendMail($data,$reason)
    {
        dispatch(new SendOfferJob($data));
    }
}
