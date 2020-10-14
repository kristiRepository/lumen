<?php

namespace App\Http\Controllers;

use App\Http\Resources\Reviews\ReviewCollection;
use App\Http\Resources\Reviews\ReviewResource;
use App\Review;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function index($trip)
    {

        return ReviewCollection::collection(Review::where('trip_id', $trip)->paginate(10));
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $trip_id
     * @return void
     */
    public function store(Request $request, $trip_id)
    {


        $trip = Trip::findOrFail($trip_id);

        if (!$trip->alreadyRegistered($trip_id)) {

            return $this->errorResponse('You can\' review this trip', 401);
        }

        if ($trip->isClosed()) {
            return $this->errorResponse('This trip has not happened yet', 401);
        }

        $this->validate($request, Review::$storeRules);
        $review = new Review();
        $review->trip_id = $trip_id;
        $review->body = $request->body;
        $review->rating = $request->rating;

        Auth::user()->customer->reviews()->save($review);

        return $this->successResponse($review, Response::HTTP_CREATED);
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
     * @param Request $request
     * @param [type] $review
     * @return void
     */
    public function update(Request $request, $review)
    {

        $this->validate($request, Review::$updateRules);
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

        $review->delete();

        return $this->successResponse($review);
    }
}
