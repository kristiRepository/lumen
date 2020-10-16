<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\Http\Resources\Agencies\AgencyCollection;
use App\Http\Resources\Agencies\AgencyResource;
use App\Jobs\SendOfferJob;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
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

        return AgencyCollection::collection(Agency::paginate(10));
    }



    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @return void
     */
    public function profile()
    {
        $agency = Auth::user()->agency;

        return $this->successResponse(new AgencyResource($agency));
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $agency
     * @return void
     */
    public function update(Request $request)
    {

        $agency = Auth::user()->agency;


        $this->validate($request, $agency->user->updateRulesAgency);
        $agency->fill($request->all());

        if ($agency->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $agency->save();


        return $this->successResponse($agency);
    }

    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @return void
     */
    public function destroy()
    {

        $agency = Auth::user()->agency;


        $agency->user->delete();
        $agency->delete();

        return $this->successResponse($agency);
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPreviousTripsReports()
    {

        $previous_trips = Trip::getTrips('<');


        $trips = Auth::user()->agency
            ->trips()
            ->where('start_date', '<', date('Y-m-d'))
            ->get();

        $ratings = $trips->map(function ($rating) {

            return [
                $rating->title => $rating->reviews->avg('rating')
            ];
        })->toArray();



        // dd($ratings->load('reviews');
        // }));
        // foreach ($previous_trips as $trip) {

        //     $data = Trip::where('title', $trip)->first();
        //     if (Trip::where('title', $trip)->first()->reviews->count() != 0) {
        //         $ratings[$trip] = $data->reviews->avg('rating');
        //     } else {
        //         $ratings[$trip] = 'This trip has no ratings yet';
        //     }
        // }


        // $participants_per_trip = [];
        // foreach ($previous_trips as $trip) {
        //     $data = Trip::where('title', $trip)->first();
        //     $participants_per_trip[$trip] = $data->numberOfParticipantsOnTrip($data->id);
        // }

        $participants_per_trip = $trips->map(function ($trip) {
            return [
                $trip->title => $trip->going
            ];
        })->toArray();


        $total_participants = $trips->sum('going');


        // $earnings_per_trip = [];
        // foreach ($previous_trips as $trip) {
        //     $data = Trip::where('title', $trip)->first();
        //     $earnings_per_trip[$trip] = $data->numberOfParticipantsOnTrip($data->id) * $data->price;
        // }

        $earnings_per_trip = $trips->map(function ($trip) {
            return [
                $trip->title => $trip->going * $trip->price
            ];
        })->toArray();



        // dd(Arr::flatten($earnings_per_trip));


        $total_earnings = array_sum(Arr::flatten($earnings_per_trip));

        $total_cost = $trips->sum('cost');


        $profit = $total_earnings - $total_cost;

        $report = [
            'pervious_trips' => $previous_trips,
            'participants_per_trip' => $participants_per_trip,
            'total_participants' => $total_participants,
            'ratings_per_trip' => $ratings,
            'earnings_per_trip' => $earnings_per_trip,
            'total_earnings' => $total_earnings,
            'total_cost' => $total_cost,
            'profit' => $profit
        ];

        return $this->successResponse($report, 200);
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getOngoingTripsReports()
    {


        // $ongoing = [];
        // foreach (Trip::getTrips('>') as $trip) {
        //     $data = Trip::where('title', $trip)->first();
        //     $ongoing[$trip] = $data->going;
        // }

        $ongoing_trips = Auth::user()->agency
            ->trips()
            ->where('start_date', '>', date('Y-m-d'))
            ->get()->map(function ($trip) {
                return [
                    $trip->title => $trip->going
                ];
            })->toArray();;


        // $participants_per_ongoing_trips = [
        //     'participants_per_ongoing_trips' => $ongoing
        // ];
        return $this->successResponse($ongoing_trips, 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function sendOffers(Request $request)
    {
        $this->dispatch(new SendOfferJob($request->all()));
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCustomerHictoric($customer)
    {


        $customers = Customer::findOrFail($customer)->trips->pluck('id')->toArray();
        $mytrips = Auth::user()->agency->trips;


        $historic = $mytrips->whereIn('id', $customers)->map(function ($trip) use ($customer) {
            if (!is_null($trip->customers->where('id', $customer)->first()->reviews->where('trip_id', $trip->id)->first())) {
                return [
                    $trip->title => $trip->customers->where('id', $customer)->first()->reviews->where('trip_id', $trip->id)->first()->rating
                ];
            } else {
                return [
                    $trip->title => 'User has not reviewed this trip'
                ];
            }
        });

        // $historic = [];
        // foreach ($mytrips as $mytrip) {
        //     if ($mytrip->reviews->count() == 0) {
        //         $historic[$mytrip->title] = 'This trip has not reviews';
        //     } elseif (!in_array($customer->id, $mytrip->reviews->pluck('customer_id')->toArray())) {
        //         $historic[$mytrip->title] = 'Customer has not reviewed this trip';
        //     } else {

        //         $historic[$mytrip->title] = $mytrip->reviews->where('customer_id', $customer->id)->first()->rating;
        //     }
        // }

        return $this->successResponse($historic, 200);
    }
}
