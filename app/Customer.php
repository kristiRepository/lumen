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

        $trips=Auth::user()->customer->trips->pluck('id')->toArray();
        return in_array($trip_id,$trips);
    }


    /**
     * Undocumented function
     *
     * @param [type] $trip_model
     * @return void
     */
    public function notAvailableOnThisDate($trip_model){

        
        $trip=Trip::findOrFail($trip_model->id);
        $customer_id=Auth::user()->customer->id;

        return ! is_null($trips=DB::table('customers')
        ->rightJoin('customer_trip','customer_trip.customer_id','=','customers.id')
        ->rightJoin('trips','customer_trip.trip_id','=','trips.id')
        ->where('customers.id',$customer_id)
        ->where('trips.start_date','<',$trip->start_date)
        ->where('trips.end_date','>',$trip->start_date)
        ->get());
        


}

}