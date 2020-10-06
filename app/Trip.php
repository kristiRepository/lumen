<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


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
}
