<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use App\Traits\ApiResponser;
use App\Trip;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    use ApiResponser;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $customerRepo;
    private $customerService;


   /**
    * Undocumented function
    *
    * @param CustomerRepository $customerRepo
    * @param CustomerService $customerService
    */
    public function __construct(CustomerRepository $customerRepo,CustomerService $customerService)
    {
        $this->customerRepo=$customerRepo;
        $this->customerService=$customerService;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {

        return $this->customerRepo->index();
        
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

        return $this->customerRepo->show($customer);

        
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
       $this->validate($request, User::$updateRulesCustomer);
      
       return $this->customerRepo->update($customer,$request);

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

        return $this->customerRepo->destroy($customer);
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

        return $this->customerService->participate($trip);

     
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

        return $this->customerService->cancel($trip);

        
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

        return $this->customerRepo->agencyHistoric($agency);
        
}

}