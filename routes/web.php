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
$router->post('/auth/customer_signup','AuthController@register_customer');
$router->post('/auth/agency_signup','AuthController@register_agency');
$router->post('/auth/login','AuthController@login');

//Logout trip
$router->get('/trips','TripController@index');
$router->get('/trips/{trip}','TripController@show');

//Logout review
$router->get('/{trip}/reviews','ReviewController@index');
$router->get('/{trip}/reviews/{review}','ReviewController@show');


$router->group(['middleware'=>'auth'],function () use ($router){

//Login trip
$router->post('/trips','TripController@store');
$router->put('/trips/{trip}','TripController@update');
$router->patch('/trips/{trip}','TripController@update');
$router->delete('/trips/{trip}','TripController@destroy');

//Login review
$router->post('/{trip}/reviews','ReviewController@store');
$router->put('/{trip}/reviews/{review}','ReviewController@update');
$router->patch('/{trip}/reviews/{review}','ReviewController@update');
$router->delete('/{trip}/reviews/{review}','ReviewController@destroy');
});




});



