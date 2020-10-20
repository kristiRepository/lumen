<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Mail\SendPasswordVerificationMailable;
use App\Mail\SendVerificationMailable;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService extends Controller implements ServiceInterface
{

    use ApiResponser;




    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function verifyCustomer($request)
    {

        event(new \App\Events\SignUpCustomerEvent($request));
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function verifyAgency($request)
    {

        event(new \App\Events\SignUpAgencyEvent($request));
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function login($request)
    {

        $input = $request->only('email', 'password');


        if (!$authorized = Auth::attempt($input)) {
            return $this->errorResponse('User is not authorized', 401);
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user->verified == 0 && date('Y-m-d') > $user->created_at->addDays(10)) {
                return $this->errorResponse('User is not verified', 403);
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
     * @param [type] $request
     * @return void
     */
    public function sendMail($request)
    {
        if (is_null(User::where('email', $request->email)->first())) {
            return $this->errorResponse('There is no user with this email', 403);
        };

        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new SendVerificationMailable($user->v_key));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function signout()
    {

        Auth::invalidate(true);
        return $this->successResponse('User logged out succesfully');
    }


    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function changePassword($request)
    {

        $user = User::find(Auth::user()->id);

        if (Hash::check($request->old_password, Auth::user()->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->successResponse($user);
        } else {
            return $this->errorResponse('Old password is not correct', 401);
        }
    }


    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function verifyEmail($request)
    {
        if (!$request->has('vkey')) {
            return  $this->errorResponse('An error occured while verifying email', 401);
        }

        if (is_null(User::where('v_key', $request->vkey)->first())) {
            return  $this->errorResponse('Can \'t identify user ', 401);
        }
        $user = User::where('v_key', $request->vkey)->first();

        if ($user->verified == 1) {
            return $this->errorResponse('This user is already verified', 401);
        }

        $user->verified = true;
        $user->save();
        return $this->successResponse('Email verified succesfullly');
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function resetPassword($request)
    {

        if (is_null(User::where('email', $request->email)->first())) {
            return  $this->errorResponse('Can \'t identify user ', 401);
        }
        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new SendPasswordVerificationMailable($user->v_key));
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function newPassword($request)
    {


        if (!$request->has('vkey')) {
            return  $this->errorResponse('An error occured while changing the password', 401);
        }


        if (is_null(User::where('v_key', $request->vkey)->first())) {
            return  $this->errorResponse('Can \'t identify user ', 401);
        }
        $user = User::where('v_key', $request->vkey)->first();
        if ($user->verified == 0) {
            return $this->errorResponse('Please verify your email first', 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->successResponse('Password changed succesfully');
    }
}
