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
$router->get('/trips','TripController@index');
$router->post('/trips','TripController@store');
$router->get('/trips/{trip}','TripController@show');
$router->put('/trips/{trip}','TripController@update');
$router->patch('/trips/{trip}','TripController@update');
$router->delete('/trips/{trip}','TripController@destroy');

});



