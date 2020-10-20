<?php

namespace App\Services;

use App\Review;
use App\Services\ServiceInterface;
use App\Trip;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReviewService implements ServiceInterface
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
     * @param [type] $trip_id
     * @return void
     */
    public function store($request, $trip_id)
    {

        $trip = Trip::findOrFail($trip_id);

        if (!$trip->alreadyRegistered($trip_id)) {


            return $this->errorResponse('You can\' review this trip', 401);
        }

        if (!$trip->isClosed()) {
            return $this->errorResponse('This trip has not happened yet', 401);
        }

        if ($trip->hasReviewOnThisTrip($trip_id)) {
            return $this->errorResponse('You already have a review on this trip', 401);
        }

        $review = new Review();
        $review->trip_id = $trip_id;
        $review->body = $request->body;
        $review->rating = $request->rating;

        Auth::user()->customer->reviews()->save($review);

        return $this->successResponse($review, Response::HTTP_CREATED);
    }
}
