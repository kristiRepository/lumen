<?php

namespace App\Http\Controllers;

use App\Http\Resources\Reviews\ReviewCollection;
use App\Http\Resources\Reviews\ReviewResource;
use App\Review;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
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

    public function index($trip){

        return ReviewCollection::collection(Review::where('trip_id',$trip)->paginate(10));


    }


    public function store(Request $request){
        
        $this->validate($request,Review::$storeRules);
        $trip=Review::create($request->all());

        return $this->successResponse($trip,Response::HTTP_CREATED);

    }

    public function show($review){

        $review=Review::findOrFail($review);
        
        return $this->successResponse(new ReviewResource($review));
        
    }

    public function update(Request $request,$review){
       
        $this->validate($request,Review::$updateRules);
        $review=Review::findOrFail($review);

        $review->fill($request->all());

        if($review->isClean()){
            return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $review->save();

        return $this->successResponse($review);
        


    }

    public function destroy($review){

        $review=Review::findOrFail($review);

        $review->delete();

        return $this->successResponse($review);
       
    }

    
}
