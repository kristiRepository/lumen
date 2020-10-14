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
$router->get('test', 'PaymentController@test');

$router->group(['prefix' => 'api'], function () use ($router) {


    $router->post('customers/paypal/execute-payment', 'PaymentController@executePayment');



    //Authentication routes
    $router->post('/auth/customer_signup', 'AuthController@registerCustomer');
    $router->post('/auth/agency-signup', 'AuthController@registerAgency');
    $router->post('/auth/login', 'AuthController@login');
    $router->get('email/verify/{user_id}', 'AuthController@verify');
    $router->get('email/resend', 'AuthController@resend');


    //Logout trip
    $router->get('/test', 'AgencyController@test');
    $router->get('/trips', 'TripController@index');
    $router->get('/trips/{trip}', 'TripController@show');

    //Logout review
    $router->get('/{trip}/reviews', 'ReviewController@index');
    $router->get('/{trip}/reviews/{review}', 'ReviewController@show');

    //Logout agency
    $router->get('/agencies', 'AgencyController@index');




    $router->group(['middleware' => 'auth'], function () use ($router) {

        //Change password
        $router->post('/auth/change-password', 'AuthController@changePassword');



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
            $router->get('/agencies/reports/previous-reports', 'AgencyController@getPreviousTripsReports');
            $router->get('/agencies/reports/ongoing-reports', 'AgencyController@getOngoingTripsReports');
            $router->post('/agencies/send/offer-email', 'AgencyController@sendOffers');
        });




        //Restricted for customers
        $router->group(['middleware' => 'is_customer'], function () use ($router) {


            $router->post('customers/paypal/create-payment', 'PaymentController@createPayment');




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
            $router->post('/customers/participate', 'CustomerController@participateOnTrip');
            $router->post('/customers/cancel_trip', 'CustomerController@cancelTrip');
        });
    });
});
