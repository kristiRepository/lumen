<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Trip extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'destination', 'start_date', 'end_date', 'max_participants', 'price', 'due_date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    public static $storeRules = [
        'title' => 'required',
        'destination' => 'required',
        'start_date' => 'required|date|date_format:Y-m-d|after:tomorrow',
        'end_date' => 'required|date|date_format:Y-m-d|after:start_at',
        'max_participants' => 'required|integer|between:10,50',
        'price' => 'required|integer|min:0',
        'due_date' => 'required|date|date_format:Y-m-d|after:today|before:start_date'
    ];

    public static $updateRules = [
        'title' => '',
        'destination' => '',
        'start_date' => 'date|date_format:Y-m-d|after:tomorrow',
        'end_date' => 'date|date_format:Y-m-d|after:start_at',
        'max_participants' => 'integer|between:10,50',
        'price' => 'integer|min:0',
        'due_date' => 'date|date_format:Y-m-d|after:today|before:start_date'
    ];


    public function reviews()
    {

        return $this->hasMany(Review::class);
    }


    public function agency()
    {

        return $this->belongsTo(Agency::class);
    }



    public function isClosed()
    {
        return $this->start_date < date('Y-m-d');
    }


    public function alreadyRegistered($trip)
    {

        if (Auth::user()->customer->trips != NULL) {
            foreach (Auth::user()->customer->trips as $c_trip) {
                if ($c_trip->id == $trip) {

                    return true;
                } else {

                    return false;
                }
            }
        }
        
        return false;
    }


    public function numberOfParticipantsOnTrip($trip){
       
        $agency=Auth::user()->agency;
        $trip=$this->findOrFail($trip);


        if($trip->isClosed()){
            return 0;
        }
        else{
             return $trip->customers->count();
        }
        
    }
}
