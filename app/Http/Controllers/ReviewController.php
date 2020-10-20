<?php

namespace App\Http\Controllers;


use App\Repositories\ReviewRepository;
use App\Review;
use App\Services\ReviewService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class ReviewController extends Controller
{
    use ApiResponser;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $reviewRepo;
    private $reviewService;

    /**
     * Undocumented function
     *
     * @param ReviewRepository $reviewRepo
     * @param ReviewService $reviewService
     */
    public function __construct(ReviewRepository $reviewRepo, ReviewService $reviewService)
    {
        $this->reviewRepo = $reviewRepo;
        $this->reviewService = $reviewService;
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function index($trip)
    {
        return $this->reviewRepo->tripReviews($trip);
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

        $this->validate($request, Review::$storeRules);
        return $this->reviewService->store($request, $trip_id);
    }

    /**
     * Undocumented function
     *
     * @param [type] $review
     * @return void
     */
    public function show($review)
    {
        return $this->reviewRepo->show($review);
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

        return $this->reviewRepo->update($request, $review);
    }

    /**
     * Undocumented function
     *
     * @param [type] $review
     * @return void
     */
    public function destroy($review)
    {
        return $this->reviewRepo->destroy($review);
    }
}
