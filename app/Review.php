<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Review extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'trip_id','body','rating'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    public function trip(){

      return $this->belongsTo(Trip::class);
    }

    public function customer(){
      
      return $this->belongsTo(Customer::class);
    }

    public static $storeRules = [
    
      'trip_id'=>'required|exists:trips,id',
      'body'=>'required',
      'rating'=>'required|integer|between:1,5',
      

  ];

  public static $updateRules = [
    
    'trip_id'=>'forbidden',
    'body'=>'',
    'rating'=>'integer|between:1,5',
    'user_id'=>'forbidden'
    

];

  
}
