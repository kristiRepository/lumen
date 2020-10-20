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


    /**
     * Undocumented function
     *
     * @return void
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $storeRules = [
        'title' => 'required',
        'destination' => 'required',
        'start_date' => 'required|date|date_format:Y-m-d|after:tomorrow',
        'end_date' => 'required|date|date_format:Y-m-d|after:start_at',
        'max_participants' => 'required|integer|between:10,50',
        'price' => 'required|integer|min:0',
        'due_date' => 'required|date|date_format:Y-m-d|after:today|before:start_date'
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $updateRules = [
        'title' => '',
        'destination' => '',
        'start_date' => 'date|date_format:Y-m-d|after:tomorrow',
        'end_date' => 'date|date_format:Y-m-d|after:start_at',
        'max_participants' => 'integer|between:10,50',
        'price' => 'integer|min:0',
        'due_date' => 'date|date_format:Y-m-d|after:today|before:start_date'
    ];


    /**
     * Undocumented function
     *
     * @return void
     */
    public function reviews()
    {

        return $this->hasMany(Review::class);
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function agency()
    {

        return $this->belongsTo(Agency::class);
    }



    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->due_date < date('Y-m-d');
    }

    public function isFull()
    {
        return $this->going == $this->max_participants;
    }


    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return void
     */
    public function alreadyRegistered($trip)
    {
        return in_array($trip, Auth::user()->customer->trips->pluck('id')->toArray());
    }


    public static function getTrips($sign)
    {
        return Auth::user()->agency
            ->trips()
            ->where('start_date', $sign, date('Y-m-d'))
            ->get()
            ->pluck('title')
            ->toArray();
    }

    /**
     * Undocumented function
     *
     * @param [type] $trip
     * @return boolean
     */
    public function hasReviewOnThisTrip($trip)
    {

        return  in_array($trip, Auth::user()->customer->reviews->pluck('trip_id')->toArray());
    }
}
