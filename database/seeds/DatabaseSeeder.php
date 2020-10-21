<?php

use App\Agency;
use App\Customer;
use App\Review;
use App\Trip;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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


        DB::table('users')->insert([
            'username'=>'Kristi',
            'email'=>'kristinano6346@gmail.com',
            'password'=>Hash::make('6346'),
            'role'=>'admin',
            'phone_number'=> '0681234567',
            'v_key'=>Str::random(32),
            'verified'=>1

        ]);
        

    }
}
