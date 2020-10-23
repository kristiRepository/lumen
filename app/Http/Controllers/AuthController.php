<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Mail\AgencyPaymentMailable;
use App\Mail\CustomerPaymentMailable;
use App\Services\AuthService;
use App\Traits\ApiResponser;
use App\Trip;
use App\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;



class AuthController extends Controller
{
    use ApiResponser;


    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $authService;



    /**
     * Undocumented function
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService=$authService;
    }

   
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function registerCustomer(Request $request)
    {

        $this->validate($request, User::$storeRulesCustomer);

        return $this->authService->verifyCustomer($request);

      
    }


   /**
    * Undocumented function
    *
    * @param Request $request
    * @return void
    */
    public function registerAgency(Request $request)
    {
        $this->validate($request, User::$storeRulesAgency);

       return $this->authService->verifyAgency($request);

        
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {

        $this->validate($request, User::$loginRules);

        return $this->authService->login($request);
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function resendVerificationEmail(Request $request){

        $this->validate($request,[
            'email'=>'required'
        ]);

        return $this->authService->sendMail($request);



    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function signout(){

        return $this->authService->signout();
        
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function changePassword(Request $request)
    {

        $this->validate($request, User::$changePasswordRules);

        return $this->authService->changePassword($request);
        
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function verifyEmail(Request $request){

        return $this->authService->verifyEmail($request);
       

    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request){
        
        return $this->authService->resetPassword($request);
        
        
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function newPassword(Request $request){

        $this->validate($request, User::$resetPasswordRules);

        return $this->authService->newPassword($request);

        


    }

    public function test(){

        $customer_id=101;
        $trip_id=20;

        $trip=Trip::findOrFail($trip_id);
        $customer=Customer::findOrFail($customer_id);
        $agency=$trip->agency;

        
        $pdf = app('dompdf.wrapper')->loadView('invoice', ['trip' => $trip,'customer'=>$customer,'agency'=>$agency]);
        $content = $pdf->download()->getOriginalContent();
        $invoice_number=DB::table('customer_trip')->where('customer_id',$customer_id)->where('trip_id',$trip_id)->first()->id;
        Storage::put('invoices/invoice'.$invoice_number.'.pdf',$content);


        dd(Storage::disk('local')->path('invoices/invoice3.pdf'));
        
        // Mail::to('kristinano6346@gmail.com')->send(new CustomerPaymentMailable($agency,$trip,$invoice_number));
        // Mail::to('kristinano6346@gmail.com')->send(new AgencyPaymentMailable($customer,$trip,$invoice_number));
        
        


        
    
    
        

    }
   
    
   
    

   

}
