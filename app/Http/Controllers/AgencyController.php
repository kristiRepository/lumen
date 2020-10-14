<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Http\Resources\Agencies\AgencyCollection;
use App\Http\Resources\Agencies\AgencyResource;
use App\Jobs\SendOfferJob;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
    public function profile($agency)
    {

        $agency = Agency::findOrFail($agency);


        if (Gate::allows('agency-profile', $agency)) {
            return $this->successResponse(new AgencyResource($agency));
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $agency
     * @return void
     */
    public function update(Request $request, $agency)
    {

        $agency = Agency::findOrFail($agency);

        if (Gate::allows('agency-profile', $agency)) {
            $this->validate($request, $agency->user->updateRulesAgency);
            $agency->fill($request->all());

            if ($agency->isClean()) {
                return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $agency->save();

            return $this->successResponse($agency);
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @return void
     */
    public function destroy($agency)
    {

        $agency = Agency::findOrFail($agency);
        if (Gate::allows('agency-profile', $agency)) {

            $agency->user->delete();
            $agency->delete();

            return $this->successResponse($agency);
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPreviousTripsReports()
    {

        $previous_trips = Trip::getTrips('<');

        $ratings = [];
        foreach ($previous_trips as $trip) {

            $data = Trip::where('title', $trip)->first();
            if (Trip::where('title', $trip)->first()->reviews->count() != 0) {
                $ratings[$trip] = $data->reviews->avg('rating');
            } else {
                $ratings[$trip] = 'This trip has no ratings yet';
            }
        }
     

        $participants_per_trip = [];
        foreach ($previous_trips as $trip) {
            $data = Trip::where('title', $trip)->first();
            $participants_per_trip[$trip] = $data->numberOfParticipantsOnTrip($data->id);
        }
        

        $total_participants = array_sum($participants_per_trip);
        

        $earnings_per_trip = [];
        foreach ($previous_trips as $trip) {
            $data = Trip::where('title', $trip)->first();
            $earnings_per_trip[$trip] = $data->numberOfParticipantsOnTrip($data->id) * $data->price;
        }

        $total_earnings =array_sum($earnings_per_trip) ;
     

        $total_cost=Trip::whereIn('title',$previous_trips)->get()->sum('cost');
       

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
    public function getOngoingTripsReports(){

        
        $ongoing=[];
        foreach(Trip::getTrips('>') as $trip){
            $data = Trip::where('title', $trip)->first();
            $ongoing[$trip]=$data->going;
        }
        $participants_per_ongoing_trips=[
            'participants_per_ongoing_trips'=>$ongoing
        ];
        return $this->successResponse($participants_per_ongoing_trips, 200);

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
    public function test()
    {
        $trips = Trip::where('due_date', '<', date('Y-m-d'))->get();

        if ($trips != NULL) {
            foreach ($trips as $trip) {
                DB::table('customer_trip')->where('trip_id', '=', $trip->id)->where('paid', '=', NULL)->delete();
            }
        }
    }
}
