<?php

namespace App\Services;


use App\Services\ServiceInterface;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TripService implements ServiceInterface
{

    use ApiResponser;

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function sendMail($data)
    {
        return;
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function store($request)
    {

        $trip = new Trip();
        $trip->title = $request->title;
        $trip->destination = $request->destination;
        $trip->start_date = $request->start_date;
        $trip->end_date = $request->end_date;
        $trip->cost = $request->cost;
        $trip->max_participants = $request->max_participants;
        $trip->going = 0;
        $trip->price = $request->price;
        $trip->due_date = $request->due_date;
        $trip->cost = $request->cost;

        Auth::user()->agency->trips()->save($trip);

        return $this->successResponse($trip, Response::HTTP_CREATED);
    }
}
