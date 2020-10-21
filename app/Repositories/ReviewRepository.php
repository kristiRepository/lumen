<?php

namespace App\Repositories;

use App\Http\Resources\Reviews\ReviewCollection;
use App\Http\Resources\Reviews\ReviewResource;
use App\Repositories\RepositoryInterface;
use App\Review;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewRepository implements RepositoryInterface
{


    use ApiResponser;


    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        return;
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function tripReviews($trip)
    {

        return ReviewCollection::collection(Review::where('trip_id', $trip)->paginate(10));
    }


    /**
     * Undocumented function
     *
     * @param [type] $review
     * @return void
     */
    public function show($review)
    {
        $review = Review::findOrFail($review);

        return $this->successResponse(new ReviewResource($review));
    }



    /**
     * Undocumented function
     *
     * @param [type] $review
     * @param [type] $request
     * @return void
     */
    public function update($review, $request)
    {
        $review = Review::findOrFail($review);

        $review->fill($request->all());

        if ($review->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $review->save();
        return $this->successResponse($review);
    }

    /**
     * Undocumented function
     *
     * @param [type] $review
     * @return void
     */
    public function destroy($review)
    {
        $review = Review::findOrFail($review);

        if(Auth::user()->customer->reviews()->where('id',$review->id)->get()->isEmpty()){
            return $this->errorResponse('You can \'t execute this action',403);
        }
        $review->delete();

        return $this->successResponse($review);
    }
}
