<?php

namespace App\Http\Controllers;

use App\Http\Resources\Trips\TripCollection;
use App\Http\Resources\Trips\TripResource;
use App\Trip;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TripController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {

        $trips = QueryBuilder::for(Trip::class)
            ->allowedFilters(['title', 'destination', 'max_price', 'upcoming'])
            ->paginate(10);



        return TripCollection::collection($trips);
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {

        $this->validate($request, Trip::$storeRules);
        $trip = new Trip;
        $trip->title = $request->title;
        $trip->destination = $request->destination;
        $trip->start_date = $request->start_date;
        $trip->end_date = $request->end_date;
        $trip->cost=$request->cost;
        $trip->max_participants = $request->max_participants;
        $trip->going=0;
        $trip->price = $request->price;
        $trip->due_date = $request->due_date;
        $trip->cost = $request->cost;

        Auth::user()->agency->trips()->save($trip);

        return $this->successResponse($trip, Response::HTTP_CREATED);
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function show($trip)
    {

        $trip = Trip::findOrFail($trip);

        return $this->successResponse(new TripResource($trip));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $trip
     * @return void
     */
    public function update(Request $request, $trip)
    {


        $this->validate($request, Trip::$updateRules);
        $trip = Trip::findOrFail($trip);
         if(is_null(Auth::user()->agency->trips()->where('trips.id','=',$trip->id)->first())){
             return $this->errorResponse('Cannot modify trip',403);
         }

        $trip->fill($request->all());

        if ($trip->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $trip->save();

        return $this->successResponse($trip);
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function destroy($trip)
    {
        $trip = Trip::findOrFail($trip);
        if(is_null(Auth::user()->agency->trips()->where('trips.id','=',$trip->id)->first())){
            return $this->errorResponse('Cannot modify trip',403);
        }
        $trip->delete();

        return $this->successResponse($trip);
    }
}
