<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'gender', 'age', 'user_id'
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
    public function trips()
    {
        return $this->belongsToMany(Trip::class);
    }

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
    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function registeredForTrip($trip_id){

        $trips=Auth::user()->customer->trips;
        foreach($trips as $trip){
            if($trip->id==$trip_id){
                return true;
            }
        }
        return false;

    }
}
