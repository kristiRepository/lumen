<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','password','email','role','phone_number'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function customer(){

        return $this->hasOne(Customer::class);
    }

    public function agency(){

        return $this->hasOne(Agency::class);
    }

    public static $storeRulesCustomer = [

        'username' => 'required|min:6|unique:users,username',
        'password' => 'required',
        'email'=>'required|email|unique:users,email',
        'phone_number'=>'required|digits:10',
        'name' => 'required',
        'surname' => 'required',
        'gender' => 'required|in:male,female',
        'age' =>'required|numeric|between:10,90',
        

    ];

    public static $storeRulesAgency = [

        'username' => 'required|min:6|unique:users,username',
        'password' => 'required',
        'email'=>'required|email|unique:users,email',
        'phone_number'=>'required|digits:10',
        'company_name' => 'required',
        'address' => 'required',
        'web'=>'required|url',
        
        

    ];


    public static $updateRulesCustomer = [

        'username' => 'min:6|unique:users,username',
        'password' => '',
        'email'=>'email|unique:users,email',
        'role'=>'in:agency,customer',
        'phone_number'=>'digits:10',
        'name' => '',
        'surname' => '',
        'gender' => 'in:male,female',
        'age' => 'numeric|between:10,90',
        'user_id'=>'forbidden'
        
    ];

    public static $updateRulesAgency = [

        'username' => 'min:6|unique:users,username',
        'password' => '',
        'email'=>'email|unique:users,email',
        'role'=>'in:agency,customer',
        'phone_number'=>'digits:10',
        'company_name' => '',
        'address' => '',
        'web' => 'url',
        'user_id'=>'forbidden'
        
    ];



}
