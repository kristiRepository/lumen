<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\Http\Resources\Agencies\AgencyCollection;
use App\Http\Resources\Agencies\AgencyResource;
use App\Jobs\SendOfferJob;
use App\Review;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $trips=DB::table('users')
        ->join('agencies', 'users.id', '=', 'agencies.user_id')
        ->rightJoin('trips','agencies.id','=','trips.agency_id')
        ->rightJoin('reviews','trips.id','=','reviews.trip_id')
        ->where('agencies.id',Auth::user()->agency->id)
        ->where('trips.start_date','<', date('Y-m-d'))
        ->select('trips.title AS title','trips.going as participants', DB::raw('avg(reviews.rating) AS rating'),DB::raw('trips.going*trips.price AS  earnings_per_trip'),'trips.cost as cost')
        ->groupBy('trip_id')
        ->get()->toArray();

        $total_earnings = array_sum(array_column($trips,'earnings_per_trip'));
        $total_cost =array_sum(array_column($trips,'cost'));
        $profit = $total_earnings - $total_cost;

        $report = [
            'trips'=>$trips,
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

        $ongoing_trips = Auth::user()->agency
            ->trips()
            ->where('start_date', '>', date('Y-m-d'))
            ->select('trips.title','trips.going')
            ->get();
            
         
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
        
        $user=Auth::user()->agency->id;
      

        $trips=DB::table('trips')
        ->rightJoin('reviews','trips.id','=','reviews.trip_id')
        ->select('trips.title','reviews.rating')
        ->where('reviews.customer_id','=',$customer)
        ->where('trips.agency_id','=',$user)->get();
       

        return $this->successResponse($trips, 200);
    }
}
