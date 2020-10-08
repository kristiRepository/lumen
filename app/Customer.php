<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','surname','gender','age','user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];


    public function trips()
    {
        return $this->belongsToMany(Trip::class);
    }

    public function reviews(){

        return $this->hasMany(Review::class);
    }

    public function user(){

        return $this->belongsTo(User::class);
    }

    
}
