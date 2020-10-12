<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Agency extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name','address','web','user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function trips(){

        return $this->hasMany(Trip::class);
    }

    public function hasTrip($trip){
        
        return Auth::user()->agency->trips->contains('id',$trip->id);
    }



   
}
