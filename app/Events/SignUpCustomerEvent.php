<?php

namespace App\Events;


use App\Customer;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;

class SignUpCustomerEvent extends Event
{
    use ApiResponser;

    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */

   

     /**
      * Undocumented function
      *
      * @param [type] $request
      */
    public function __construct($request)
    {

             $user = new User;
            $user->username = $request->username;
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->email = $request->email;
            $user->role = 'customer';
            $user->verified=0;
            $user->v_key=Str::random(32);
            $user->phone_number = $request->phone_number;
            $user->save();
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->surname = $request->surname;
            $customer->gender = $request->gender;
            $customer->age = $request->age;
            $user->customer()->save($customer);
            $this->setUser($user);
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUser()
    {
        return $this->user;
    }
}
