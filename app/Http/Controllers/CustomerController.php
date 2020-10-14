<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Resources\Customers\CustomerCollection;
use App\Http\Resources\Customers\CustomerResource;
use App\Traits\ApiResponser;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
    public function profile($customer)
    {

        $customer = Customer::findOrFail($customer);


        if (Gate::allows('customer-profile', $customer)) {
            return $this->successResponse(new CustomerResource($customer));
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $customer
     * @return void
     */
    public function update(Request $request, $customer)
    {

        $customer = Customer::findOrFail($customer);
        if (Gate::allows('customer-profile', $customer)) {
            $this->validate($request, $customer->user->updateRulesCustomer);
            $customer->fill($request->all());

            if ($customer->isClean()) {
                return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $customer->save();

            return $this->successResponse($customer);
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $customer
     * @return void
     */
    public function destroy($customer)
    {
        $customer = Customer::findOrFail($customer);

        if (Gate::allows('customer-profile', $customer)) {

            $customer->user->delete();
            $customer->delete();

            return $this->successResponse($customer);
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
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

        if ($trip->alreadyRegistered($request->trip)) {

            return $this->errorResponse('You have already registered for this trip', 401);
        }


        Auth()->user()->customer->trips()->attach($trip);
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

   
}
