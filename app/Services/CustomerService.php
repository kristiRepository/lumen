<?php

namespace App\Services;


use App\Services\ServiceInterface;
use App\Traits\ApiResponser;


class CustomerService implements ServiceInterface
{

    use ApiResponser;


    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function sendMail($data,$reason)
    {
        return;
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function participate($trip)
    {
        if ($trip->isClosed()) {
            return $this->errorResponse('This trip is closed', 401);
        }

        if ($trip->isFull()) {
            return $this->errorResponse('This trip has reached the maximum number of participants', 401);
        }


        if ($trip->alreadyRegistered($trip->id)) {

            return $this->errorResponse('You have already registered for this trip', 401);
        }

        
        if (auth()->user()->customer->notAvailableOnThisDate($trip)) {

            return $this->errorResponse('You have reserved another trip on this date', 401);
        }


        auth()->user()->customer->trips()->attach($trip);
        $trip->going = $trip->going + 1;
        $trip->save();
        return $this->successResponse('You\'ve succesfully registered for this trip');
    }


    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function cancel($trip)
    {

        if ($trip->isClosed()) {
            return $this->errorResponse('This trip is closed', 401);
        }

        if (!$trip->alreadyRegistered($trip->id)) {

            return $this->errorResponse('You are not registered for this trip', 401);
        }

        auth()->user()->customer->trips()->detach($trip);
        return $this->successResponse('You\'ve succesfully cancelled this trip');
    }
}
