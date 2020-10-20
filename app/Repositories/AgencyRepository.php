<?php

namespace App\Repositories;

use App\Agency;
use App\Customer;
use App\Http\Resources\Agencies\AgencyCollection;
use App\Http\Resources\Agencies\AgencyResource;
use App\Repositories\RepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgencyRepository implements RepositoryInterface
{


    use ApiResponser;


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
    public function show($agency)
    {

        return $this->successResponse(new AgencyResource($agency));
    }



    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @param [type] $data
     * @return void
     */
    public function update($agency, $data)
    {

        $agency->fill($data->only('company_name', 'address', 'web'));
        $user = $agency->user;
        $user->fill($data->only('username', 'email', 'phone_number'));

        if ($agency->isClean() && $$user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $agency->save();
        $user->save();

        return $this->successResponse($agency);
    }

    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @return void
     */
    public function destroy($agency)
    {

        $agency->user->delete();
        $agency->delete();

        return $this->successResponse($agency);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function previousReports()
    {

        $trips = DB::table('users')
            ->join('agencies', 'users.id', '=', 'agencies.user_id')
            ->rightJoin('trips', 'agencies.id', '=', 'trips.agency_id')
            ->rightJoin('reviews', 'trips.id', '=', 'reviews.trip_id')
            ->where('agencies.id', Auth::user()->agency->id)
            ->where('trips.start_date', '<', date('Y-m-d'))
            ->select('trips.title AS title', 'trips.going as participants', DB::raw('avg(reviews.rating) AS rating'), DB::raw('trips.going*trips.price AS  earnings_per_trip'), 'trips.cost as cost')
            ->groupBy('trip_id')
            ->get()->toArray();

        $total_earnings = array_sum(array_column($trips, 'earnings_per_trip'));
        $total_cost = array_sum(array_column($trips, 'cost'));
        $profit = $total_earnings - $total_cost;

        $report = [
            'trips' => $trips,
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
    public function ongoingReports()
    {

        $ongoing_trips = Auth::user()->agency
            ->trips()
            ->where('start_date', '>', date('Y-m-d'))
            ->select('trips.title', 'trips.going')
            ->get();


        return $this->successResponse($ongoing_trips, 200);
    }

    /**
     * Undocumented function
     *
     * @param [type] $customer
     * @return void
     */
    public function customerHictoric($customer)
    {

        $user = Auth::user()->agency->id;
        $customer = Customer::findOrFail($customer);

        $trips = DB::table('trips')
            ->rightJoin('reviews', 'trips.id', '=', 'reviews.trip_id')
            ->select('trips.title', 'reviews.rating')
            ->where('reviews.customer_id', '=', $customer->id)
            ->where('trips.agency_id', '=', $user)->get();

        return $this->successResponse($trips, 200);
    }
}
