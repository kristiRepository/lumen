<?php

namespace App\Http\Controllers;

use App\Http\Resources\Trips\TripCollection;
use App\Http\Resources\Trips\TripResource;
use App\Trip;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

    public function index(Request $request){

    if($request->has('title')){
        return TripCollection::collection(Trip::where('title','like','%'.$request->title.'%')->paginate(10));
    }

    if($request->has('destination')){
        return TripCollection::collection(Trip::where('destination','=',$request->destination)->paginate(10));
    }
    if($request->has('max_price')){
        return TripCollection::collection(Trip::where('price','<=',$request->max_price)->paginate(10));
    }
    if($request->has('upcoming')){
        return TripCollection::collection(Trip::where('start_date','>',date('Y-m-d'))->paginate(10));
    }

    return TripCollection::collection(Trip::paginate(10));
     

    }


    public function store(Request $request){
        
        $this->validate($request,Trip::$storeRules);
        $trip=new Trip;
        $trip->title=$request->title;
        $trip->destination=$request->destination;
        $trip->start_date=$request->start_date;
        $trip->end_date=$request->end_date;
        $trip->max_participants=$request->max_participants;
        $trip->price=$request->price;
        $trip->due_date=$request->due_date;

         Auth::user()->agency->trips()->save($trip);

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
