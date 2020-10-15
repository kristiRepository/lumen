<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\Events\Event;
use App\Traits\ApiResponser;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event as FacadesEvent;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
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

   
    public function registerCustomer(Request $request)
    {

        $this->validate($request, User::$storeRulesCustomer);


        try {

            $user = new User;
            $user->username = $request->username;
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->email = $request->email;
            $user->role = 'customer';
            $user->phone_number = $request->phone_number;
            $user->save();
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->surname = $request->surname;
            $customer->gender = $request->gender;
            $customer->age = $request->age;
            $user->customer()->save($customer);

            return $this->successResponse($user->load('customer'));
        } catch (Exception $e) {

            return $this->errorResponse('An error occured while creating user', 500);
        }
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

        event(new \App\Events\SignUpAgencyEvent($request));

        
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


        $input = $request->only('email', 'password');


        if (!$authorized = Auth::attempt($input)) {
            return $this->errorResponse('User is not authorized', 401);
        } else {
            $user = User::where('email', $request->email)->first();
            if($user->verified==0){
                return $this->errorResponse('User is not verified',403);
            }

            if ($user->role == 'agency') {
                $user = $user->load('agency');
                return $this->respondWithToken($user, $authorized);
            } else {
                $user = $user->load('customer');
                return $this->respondWithToken($user, $authorized);
            }
        }
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
        $user = User::find($user = Auth::user()->id);


        if (Hash::check($request->old_password, Auth::user()->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->successResponse($user);
        } else {
            return $this->errorResponse('Old password is not correct', 401);
        }
    }

    public function verifyEmail($user,Request $request){

        if(! $request->has('vkey')){
            $this->errorResponse('An error occured while verifying email',401);
        }
        $user=User::findOrFail($user);
       if($user->v_key != $request->vkey){
        $this->errorResponse('Can\'t verify email',401);
       }

       $user->verified=true;
       $user->save();
       return $this->successResponse('Email verified succesfullly');

    }
}
