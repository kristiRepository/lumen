<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
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

$factory->define(Customer::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'surname' => $faker->word,
        'gender' => $faker->randomElement(['male', 'female']),
        'age' => $faker->numberBetween(10, 90),
        'user_id' => User::all()->random()->id

    ];
});
