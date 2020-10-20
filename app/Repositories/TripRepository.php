<?php

namespace App\Repositories;

use App\Http\Resources\Trips\TripCollection;
use App\Http\Resources\Trips\TripResource;
use App\Repositories\RepositoryInterface;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class TripRepository implements RepositoryInterface
{


    use ApiResponser;


    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {

        $trips = QueryBuilder::for(Trip::class)
            ->allowedFilters(['title', 'destination', 'max_price', 'upcoming'])
            ->paginate(10);

        return TripCollection::collection($trips);
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
     * @param [type] $request
     * @param [type] $trip
     * @return void
     */
    public function update($request, $trip)
    {

        $trip = Trip::findOrFail($trip);
        if (is_null(Auth::user()->agency->trips()->where('trips.id', '=', $trip->id)->first())) {
            return $this->errorResponse('Cannot modify trip', 403);
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
        if (is_null(Auth::user()->agency->trips()->where('trips.id', '=', $trip->id)->first())) {
            return $this->errorResponse('Cannot modify trip', 403);
        }
        $trip->delete();

        return $this->successResponse($trip);
    }
}
