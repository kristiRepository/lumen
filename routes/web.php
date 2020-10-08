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

$router->post('/auth/customer_signup','AuthController@register_customer');
$router->post('/auth/agency_signup','AuthController@register_agency');


$router->get('/trips','TripController@index');
$router->post('/trips','TripController@store');
$router->get('/trips/{trip}','TripController@show');
$router->put('/trips/{trip}','TripController@update');
$router->patch('/trips/{trip}','TripController@update');
$router->delete('/trips/{trip}','TripController@destroy');


$router->get('/{trip}/reviews','ReviewController@index');
$router->post('/{trip}/reviews','ReviewController@store');
$router->get('/{trip}/reviews/{review}','ReviewController@show');
$router->put('/{trip}/reviews/{review}','ReviewController@update');
$router->patch('/{trip}/reviews/{review}','ReviewController@update');
$router->delete('/{trip}/reviews/{review}','ReviewController@destroy');

});



