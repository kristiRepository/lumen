<?php

namespace App\Http\Controllers;

use App\Http\Resources\Trips\TripCollection;
use App\Http\Resources\Trips\TripResource;
use App\Trip;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function index(){

     return TripCollection::collection(Trip::paginate(10));

    }

    public function store(Request $request){
        
        $this->validate($request,Trip::$storeRules);
        $trip=Trip::create($request->all());

        return $this->successResponse($trip,Response::HTTP_CREATED);


    }

    public function show($trip){

        $trip=Trip::findOrFail($trip);
        
        return $this->successResponse(new TripResource($trip));
    }

    public function update(Request $request,$trip){

        $this->validate($request,Trip::$updateRules);
        $trip=Trip::findOrFail($trip);

        $trip->fill($request->all());

        if($trip->isClean()){
            return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $trip->save();

        return $this->successResponse($trip);


    }

    public function destroy($trip){

        $trip=Trip::findOrFail($trip);

        $trip->delete();

        return $this->successResponse($trip);
    }

    
}
