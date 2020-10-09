<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\Traits\ApiResponser;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function register_customer(Request $request)
    {

        $this->validate($request, User::$storeRulesCustomer);
        

        try {

            $user = new User;
            $user->username = $request->username;
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->email = $request->email;
            $user->role ='customer';
            $user->phone_number = $request->phone_number;
            $user->save();
            $customer=new Customer();
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


    public function register_agency(Request $request)
    {

        $this->validate($request, User::$storeRulesAgency);
        

        try {

            $user = new User;
            $user->username = $request->username;
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->email = $request->email;
            $user->role ='agency';
            $user->phone_number = $request->phone_number;
            $user->save();
            $agency=new Agency();
            $agency->company_name = $request->company_name; 
            $agency->address = $request->address;
            $agency->web= $request->web;
            $user->agency()->save($agency);

            return $this->successResponse($user->load('agency'));


        } catch (Exception $e) {

            return $this->errorResponse('An error occured while creating user', 500);
        }
    }

    public function login(Request $request){

        $this->validate($request,User::$loginRules);

        $input=$request->only('email','password');
        

        if(! $authorized=Auth::attempt($input)){
            return $this->errorResponse('User is not authorized',401);
        }
        else {
            $user=User::where('email',$request->email)->first();
            
            if($user->role=='agency'){
                $user=$user->load('agency');
            return $this->respondWithToken($user,$authorized);
            }
            else{
                $user=$user->load('customer');
                return $this->respondWithToken($user,$authorized);
            }


            
        }


    }

    public function change_password(Request $request){

        
        $this->validate($request,User::$changePasswordRules);
        $user=User::find($user=Auth::user()->id);
        
        
        if (Hash::check($request->old_password, Auth::user()->password)) { 
            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->successResponse($user);

    }
    else{
        return $this->errorResponse('Old password is not correct',401);
    }
}


}
