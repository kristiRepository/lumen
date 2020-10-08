<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Customer;
use App\Traits\ApiResponser;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            $user->customer()->save($agency);

            return $this->successResponse($user->load('agency'));


        } catch (Exception $e) {

            return $this->errorResponse('An error occured while creating user', 500);
        }
    }
}
