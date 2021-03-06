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
    'trip_id', 'body', 'rating'
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
  public function trip()
  {

    return $this->belongsTo(Trip::class);
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  public function customer()
  {

    return $this->belongsTo(Customer::class);
  }

  /**
   * Undocumented variable
   *
   * @var array
   */
  public static $storeRules = [


    'body' => 'required',
    'rating' => 'required|integer|between:1,5',


  ];

  /**
   * Undocumented variable
   *
   * @var array
   */
  public static $updateRules = [


    'body' => '',
    'rating' => 'integer|between:1,5',
    'user_id' => 'forbidden'


  ];
}
