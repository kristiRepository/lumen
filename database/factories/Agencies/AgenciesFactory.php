<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agency;
use App\User;
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


$factory->define(Agency::class, function (Faker $faker) {

    return [
        'company_name' => $faker->word,
        'address' => $faker->word,
        'web' => $faker->url,
        'user_id' => User::all()->random()->id

    ];
});
