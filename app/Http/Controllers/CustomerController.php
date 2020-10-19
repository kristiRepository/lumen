<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\Http\Resources\Customers\CustomerCollection;
use App\Http\Resources\Customers\CustomerResource;
use App\Review;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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
     * @return void
     */
    public function index()
    {

        return CustomerCollection::collection(Customer::paginate(10));
    }


    /**
     * Undocumented function
     *
     * @param [type] $customer
     * @return void
     */
    public function profile()
    {

        $customer = Auth::user()->customer;

        return $this->successResponse(new CustomerResource($customer));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $customer
     * @return void
     */
    public function update(Request $request)
    {

        $customer = Auth::user()->customer;

        $this->validate($request, $customer->user->updateRulesCustomer);
        $customer->fill($request->all());

        if ($customer->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer->save();

        return $this->successResponse($customer);
    }

    /**
     * Undocumented function
     *
     * @param [type] $customer
     * @return void
     */
    public function destroy()
    {
        $customer = Auth::user()->customer;

        $customer->user->delete();
        $customer->delete();
        return $this->successResponse($customer);
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function participateOnTrip(Request $request)
    {

        $trip = Trip::findOrFail($request->trip);

        if ($trip->isClosed()) {
            return $this->errorResponse('This trip is closed', 401);
        }

        if ($trip->isFull()) {
            return $this->errorResponse('This trip has reached the maximum number of participants', 401);
        }


        if ($trip->alreadyRegistered($request->trip)) {

            return $this->errorResponse('You have already registered for this trip', 401);
        }

        if (auth()->user()->customer->notAvailableOnThisDate($trip)) {

            return $this->errorResponse('You have reserved another trip on this date', 401);
        }


        Auth()->user()->customer->trips()->attach($trip);
        $trip->going = $trip->going + 1;
        $trip->save();
        return $this->successResponse('You\'ve succesfully registered for this trip');
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function cancelTrip(Request $request)
    {

        $trip = Trip::findOrFail($request->trip);

        if ($trip->isClosed()) {
            return $this->errorResponse('This trip is closed', 401);
        }

        if (!$trip->alreadyRegistered($request->trip)) {

            return $this->errorResponse('You are not registered for this trip', 401);
        }

        Auth()->user()->customer->trips()->detach($trip);
        return $this->successResponse('You\'ve succesfully cancelled this trip');
    }

    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @return void
     */
    public function getAgencyHictoric($agency)
    {


        $agency = Agency::findOrFail($agency);
        $trips = $agency->trips->where('start_date', '>', date('Y-m-d'))->pluck('title')->toArray();

      
        $ratings = DB::table('trips')
           ->join('reviews', 'trips.id', '=', 'reviews.trip_id')
           ->select('trips.title', DB::raw('avg(reviews.rating) AS average'))
           ->where('trips.start_date', '<', date('Y-m-d'))
           ->groupBy('trip_id','title')
           ->get();

        
        
      
        $data = [
            'ongoing_trips' => $trips,
            'previous_trips' => $ratings
        ];
        return $this->successResponse($data, 200);
    }
}
