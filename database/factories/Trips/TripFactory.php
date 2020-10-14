<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agency;
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

$factory->define(Trip::class, function (Faker $faker) {

    $start = $faker->dateTimeBetween('now', '+2 years');
    $end = $faker->dateTimeBetween($start->format('Y-m-d H:i:s') . ' +1 days', $start->format('Y-m-d H:i:s') . ' +8 days');

    return [
        'title' => $faker->word,
        'destination' => $faker->state,
        'start_date' => $start,
        'end_date' => $end,
        'max_participants' => $faker->numberBetween($min = 10, $max = 50),
        'price' => $faker->numberBetween($min = 110, $max = 400),
        'due_date' => $faker->dateTimeBetween('now', $start),
        'cost' => $faker->numberBetween($min = 20, $max = 100),
        'agency_id' => Agency::all()->random()->id



    ];
});
