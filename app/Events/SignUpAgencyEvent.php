<?php

namespace App\Events;

use App\Agency;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;

class SignUpAgencyEvent extends Event
{
    use ApiResponser;

    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */

   

    public function __construct($request)
    {

            $user = new User();
            $user->username = $request->username;
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->email = $request->email;
            $user->role = 'agency';
            $user->verified=0;
            $user->v_key=Str::random(32);
            $user->phone_number = $request->phone_number;
            $user->save();
            $agency = new Agency();
            $agency->company_name = $request->company_name;
            $agency->address = $request->address;
            $agency->web = $request->web;
            $user->agency()->save($agency);
            $this->setUser($user);
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
