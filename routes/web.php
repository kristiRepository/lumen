<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {

    //Authentication routes
    $router->post('/auth/customer_signup', 'AuthController@register_customer');
    $router->post('/auth/agency_signup', 'AuthController@register_agency');
    $router->post('/auth/login', 'AuthController@login');

    //Logout trip
    $router->get('/trips', 'TripController@index');
    $router->get('/trips/{trip}', 'TripController@show');

    //Logout review
    $router->get('/{trip}/reviews', 'ReviewController@index');
    $router->get('/{trip}/reviews/{review}', 'ReviewController@show');

    //Logout agency
    $router->get('/agencies', 'AgencyController@index');


    $router->group(['middleware' => 'auth'], function () use ($router) {



        //Restricted for agencies
        $router->group(['middleware' => 'is_agency'], function () use ($router) {

            //Trip Actions
            $router->post('/trips', 'TripController@store');
            $router->put('/trips/{trip}', 'TripController@update');
            $router->patch('/trips/{trip}', 'TripController@update');
            $router->delete('/trips/{trip}', 'TripController@destroy');

            //Customers
            $router->get('/customers', 'CustomerController@index');


            //Profile agency
            $router->get('/agencies/{agency}', 'AgencyController@profile');
            $router->delete('/agencies/{agency}', 'AgencyController@destroy');
            $router->put('/agencies/{agency}', 'AgencyController@update');
            $router->patch('/agencies/{agency}', 'AgencyController@update');
        });




        //Restricted for customers
        $router->group(['middleware' => 'is_customer'], function () use ($router) {


            //Login review
            $router->post('/{trip_id}/reviews', 'ReviewController@store');
            $router->put('/{trip}/reviews/{review}', 'ReviewController@update');
            $router->patch('/{trip}/reviews/{review}', 'ReviewController@update');
            $router->delete('/{trip}/reviews/{review}', 'ReviewController@destroy');

            //Profile customer
            $router->get('/customers/{customer}', 'CustomerController@profile');
            $router->delete('/customers/{customer}', 'CustomerController@destroy');
            $router->put('/customers/{customer}', 'CustomerController@update');
            $router->patch('/customers/{customer}', 'CustomerController@update');


            //Trip actions
            $router->post('/customers/participate', 'CustomerController@participate_on_trip');
            $router->post('/customers/cancel_trip', 'CustomerController@cancel_trip');
        });
    });
});
