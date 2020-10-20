<?php

namespace App\Http\Controllers;


use App\Repositories\AgencyRepository;
use App\Services\AgencyService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AgencyController extends Controller
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $agencyRepo;
    private $agencyService;



    /**
     * Undocumented function
     *
     * @param AgencyRepository $agencyRepo
     * @param AgencyService $agencyService
     */
    public function __construct(AgencyRepository $agencyRepo,AgencyService $agencyService)
    {

        $this->agencyRepo=$agencyRepo;
        $this->agencyService=$agencyService;

    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {

        return $this->agencyRepo->index();
        
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
        return $this->agencyRepo->show($agency);

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
       $this->validate($request, User::$updateRulesAgency);
      
       return $this->agencyRepo->update($agency,$request);
   
      
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
        return $this->agencyRepo->destroy($agency);

    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPreviousTripsReports()
    {

        return $this->agencyRepo->previousReports();
        
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getOngoingTripsReports()
    {

        return $this->agencyRepo->ongoingReports();
        
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function sendOffers(Request $request)
    {
        $this->agencyService->sendMail($request->all());
        
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCustomerHictoric($customer)
    {
    
        return $this->agencyRepo->customerHictoric($customer);
      

    }
}
