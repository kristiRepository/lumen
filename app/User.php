<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{

    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'email', 'role', 'phone_number'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function customer()
    {

        return $this->hasOne(Customer::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function agency()
    {

        return $this->hasOne(Agency::class);
    }

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $storeRulesCustomer = [

        'username' => 'required|min:6|unique:users,username',
        'password' => 'required',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|digits:10',
        'name' => 'required',
        'surname' => 'required',
        'gender' => 'required|in:male,female',
        'age' => 'required|numeric|between:10,90',


    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $storeRulesAgency = [

        'username' => 'required|min:6|unique:users,username',
        'password' => 'required',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|digits:10',
        'company_name' => 'required',
        'address' => 'required',
        'web' => 'required|url',

    ];


    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $updateRulesCustomer = [

        'username' => 'min:6|unique:users,username',
        'email' => 'email|unique:users,email',
        'phone_number' => 'digits:10',
        'name' => '',
        'surname' => '',
        'gender' => 'in:male,female',
        'age' => 'numeric|between:10,90',
        'user_id' => 'forbidden'

    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $updateRulesAgency = [

        'username' => 'min:6|unique:users,username',
        'email' => 'email|unique:users,email',
        'phone_number' => 'digits:10',
        'company_name' => '',
        'address' => '',
        'web' => 'url',
        'user_id' => 'forbidden'

    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $loginRules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $changePasswordRules = [
        'old_password' => 'required',
        'new_password' => 'required',
        'confirm_password' => 'required|same:new_password'
    ];

    public static $resetPasswordRules = [
        'vkey' => '',
        'new_password' => 'required',
        'confirm_password' => 'required|same:new_password'
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
