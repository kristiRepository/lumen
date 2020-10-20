<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Mail\SendPasswordVerificationMailable;
use App\Mail\SendVerificationMailable;
use App\Traits\ApiResponser;
use App\Trip;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



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

   
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function registerCustomer(Request $request)
    {

        $this->validate($request, User::$storeRulesCustomer);

        event(new \App\Events\SignUpCustomerEvent($request));
 
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
            if($user->verified==0 && date('Y-m-d')>$user->created_at->addDays(10)){
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
    public function resendVerificationEmail(Request $request){

        $this->validate($request,[
            'email'=>'required'
        ]);

        if(is_null(User::where('email',$request->email)->first())){
            return $this->errorResponse('There is no user with this email',403);
        };

        $user=User::where('email',$request->email)->first();
        Mail::to($user->email)->send(new SendVerificationMailable($user->v_key));


    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function signout(){

        Auth::invalidate(true);
         
        return $this->successResponse('User logged out succesfully');
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
     * @param Request $request
     * @return void
     */
    public function verifyEmail(Request $request){

        if(! $request->has('vkey')){
           return  $this->errorResponse('An error occured while verifying email',401);
        }
      
        if(is_null(User::where('v_key',$request->vkey)->first())){
            return  $this->errorResponse('Can \'t identify user ',401);
        }
        $user=User::where('v_key',$request->vkey)->first();
        
        if($user->verified == 1){
           return $this->errorResponse('This user is already verified',401);

        }

       $user->verified=true;
       $user->save();
       return $this->successResponse('Email verified succesfullly');

    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request){
        
        if(is_null(User::where('email',$request->email)->first())){
            return  $this->errorResponse('Can \'t identify user ',401);
        }
       $user=User::where('email',$request->email)->first();
        Mail::to($user->email)->send(new SendPasswordVerificationMailable($user->v_key));
        
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function newPassword(Request $request){

        $this->validate($request, User::$resetPasswordRules);

        if(! $request->has('vkey')){
            return  $this->errorResponse('An error occured while changing the password',401);
         }

         
        if(is_null(User::where('v_key',$request->vkey)->first())){
            return  $this->errorResponse('Can \'t identify user ',401);
        }
        $user=User::where('v_key',$request->vkey)->first();
        if($user->verified ==0){
            return $this->errorResponse('Please verify your email first',401);
        }

        $user->password=Hash::make($request->new_password);
        $user->save();

        return $this->successResponse('Password changed succesfully');


    }

   

}
