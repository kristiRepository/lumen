<?php

namespace App\Repositories;

use App\Customer;
use App\Http\Resources\Customers\CustomerCollection;
use App\Http\Resources\Customers\CustomerResource;
use App\Repositories\RepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements RepositoryInterface
{


    use ApiResponser;

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
    public function show($customer)
    {

        return $this->successResponse(new CustomerResource($customer));
    }


    /**
     * Undocumented function
     *
     * @param [type] $customer
     * @param [type] $data
     * @return void
     */
    public function update($customer, $data)
    {

        $customer->fill($data->only('name', 'surname', 'gender', 'age'));
        $user = $customer->user;
        $user->fill($data->only('username', 'email', 'phone_number'));

        if ($customer->isClean() && $$user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $customer->save();
        $user->save();

        return $this->successResponse($customer);
    }

    /**
     * Undocumented function
     *
     * @param [type] $customer
     * @return void
     */
    public function destroy($customer)
    {

        $customer->user->delete();
        $customer->delete();

        return $this->successResponse($customer);
    }

    /**
     * Undocumented function
     *
     * @param [type] $agency
     * @return void
     */
    public function agencyHistoric($agency)
    {
        $trips = $agency->trips->where('start_date', '>', date('Y-m-d'))->pluck('title')->toArray();


        $ratings = DB::table('trips')
            ->join('reviews', 'trips.id', '=', 'reviews.trip_id')
            ->select('trips.title', DB::raw('avg(reviews.rating) AS average'))
            ->where('trips.start_date', '<', date('Y-m-d'))
            ->groupBy('trip_id', 'title')
            ->get();

        $data = [
            'ongoing_trips' => $trips,
            'previous_trips' => $ratings
        ];
        return $this->successResponse($data, 200);
    }
}
