<?php

namespace App\Http\Controllers;


use App\Services\AuthService;
use App\Traits\ApiResponser;
use App\User;
use Illuminate\Http\Request;




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

   

}
