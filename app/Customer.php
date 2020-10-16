<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


    /**
     * Undocumented function
     *
     * @param [type] $trip_id
     * @return void
     */
    public function registeredForTrip($trip_id){

        $trips=Auth::user()->customer->trips;
        foreach($trips as $trip){
            if($trip->id==$trip_id){
                return true;
            }
        }
        return false;

    }


    /**
     * Undocumented function
     *
     * @param [type] $trip_model
     * @return void
     */
    public function notAvailableOnThisDate($trip_model){
        

        $trips=Trip::whereIn('id',(DB::table('customer_trip')
             ->where('customer_id','=',auth()->user()->customer->id)
             ->get('trip_id')->pluck('trip_id')
             ->toArray()))
             ->get();
    

        foreach($trips as $trip){
            if($trip_model->start_date>$trip->start_date && $trip_model->start_date<$trip->end_date){
                return true;
            }
        }
        return false;
    }


}
