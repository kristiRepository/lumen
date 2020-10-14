<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use App\Review;
use App\Trip;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Review::class, function (Faker $faker) {

    return [
        'trip_id' => Trip::all()->random()->id,
        'customer_id' => Customer::all()->random()->id,
        'body' => $faker->text,
        'rating' => $faker->numberBetween(1, 5)
    ];
});
