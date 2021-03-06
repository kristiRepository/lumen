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


$router->get('test','AuthController@test');
$router->group(['prefix' => 'api'], function () use ($router) {

    //Execute payment
    $router->post('customers/paypal/execute-payment', 'PaymentController@executePayment');

    //Verify email
    $router->get('/verify', 'AuthController@verifyEmail');
    $router->post('/resend-verification', 'AuthController@resendVerificationEmail');

    //Reset password
    $router->post('/reset-password', 'AuthController@resetPassword');
    $router->post('/new-password', 'AuthController@newPassword');


    //Authentication routes
    $router->post('/auth/customer-signup', 'AuthController@registerCustomer');
    $router->post('/auth/agency-signup', 'AuthController@registerAgency');
    $router->post('/auth/login', 'AuthController@login');
    $router->get('email/verify/{user_id}', 'AuthController@verify');
    $router->get('email/resend', 'AuthController@resend');


    //Logout trip
    $router->get('/trips', 'TripController@index');
    $router->get('/trips/{trip}', 'TripController@show');

    //Logout review
    $router->get('/{trip}/reviews', 'ReviewController@index');
    $router->get('/reviews/{review}', 'ReviewController@show');

    //Logout agency
    $router->get('/agencies', 'AgencyController@index');




    $router->group(['middleware' => 'auth'], function () use ($router) {

        
        $router->group(['prefix'=>'admin','middleware' => 'is_admin'], function () use ($router) {

            // Admin reviews functions
            $router->get('/all-reviews','AdminController@allReviews');
            $router->post('/reviews/{review}','AdminController@deleteInappropriateReviews');

            //Admin login as user
            $router->post('/login-as-user','AdminController@loginAsUser');

        });

        //Change password
        $router->post('/auth/change-password', 'AuthController@changePassword');

        //Sign out
        $router->post('/auth/signout', 'AuthController@signout');



        //Restricted for agencies
        $router->group(['middleware' => 'is_agency'], function () use ($router) {

            //Trip Actions
            $router->post('/trips', 'TripController@store');
            $router->put('/trips/{trip}', 'TripController@update');
            $router->patch('/trips/{trip}', 'TripController@update');
            $router->delete('/trips/{trip}', 'TripController@destroy');

            //Customers
            $router->get('/customers', 'CustomerController@index');
            $router->get('/customers/{customer}', 'AgencyController@getCustomerHictoric');


            //Profile agency
            $router->get('/profile/agencies', 'AgencyController@profile');
            $router->delete('/agencies', 'AgencyController@destroy');
            $router->put('/agencies', 'AgencyController@update');
            $router->patch('/agencies', 'AgencyController@update');
            $router->get('/agencies/reports/previous-reports', 'AgencyController@getPreviousTripsReports');
            $router->get('/agencies/reports/ongoing-reports', 'AgencyController@getOngoingTripsReports');
            $router->post('/agencies/send/offer-email', 'AgencyController@sendOffers');
        });



        //Restricted for customers
        $router->group(['middleware' => 'is_customer'], function () use ($router) {

            //Create Payment
            $router->post('customers/paypal/create-payment', 'PaymentController@createPayment');



            //Login review
            $router->post('/{trip_id}/reviews', 'ReviewController@store');
            $router->put('reviews/{review}', 'ReviewController@update');
            $router->patch('reviews/{review}', 'ReviewController@update');
            $router->delete('reviews/{review}', 'ReviewController@destroy');

            //Profile customer
            $router->get('/profile/customers', 'CustomerController@profile');
            $router->delete('/customers', 'CustomerController@destroy');
            $router->put('/customers', 'CustomerController@update');
            $router->patch('/customers', 'CustomerController@update');


            //Agencies historic
            $router->get('/agencies/{agency}', 'CustomerController@getAgencyHictoric');


            //Trip actions
            $router->post('/customers/participate', 'CustomerController@participateOnTrip');
            $router->post('/customers/cancel_trip', 'CustomerController@cancelTrip');
        });
    });
});
