<?php

use App\Agency;
use App\Customer;
use App\Review;
use App\Trip;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class,100)->create();
        factory(Customer::class,100)->create();
        factory(Agency::class,100)->create();
        factory(Trip::class,20)->create();
        factory(Review::class,60)->create();
        

    }
}
