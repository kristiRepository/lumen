<?php

namespace App\Http\Controllers;


use App\Repositories\TripRepository;
use App\Services\TripService;
use App\Trip;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class TripController extends Controller
{
    use ApiResponser;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $tripRepo;
    private $tripService;


    /**
     * Undocumented function
     *
     * @param TripRepository $tripRepo
     * @param TripService $tripService
     */
    public function __construct(TripRepository $tripRepo,TripService $tripService)
    {
        $this->tripRepo=$tripRepo;
        $this->tripService=$tripService;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index()
    {
        return $this->tripRepo->index();

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
        
        return $this->tripService->store($request);
        
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function show($trip)
    {
        return $this->tripRepo->show($trip);
        
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
        return $this->tripRepo->update($request,$trip);
        
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function destroy($trip)
    {
        return $this->tripRepo->destroy($trip);
        
    }
}
