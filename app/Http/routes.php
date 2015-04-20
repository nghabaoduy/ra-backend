<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');


$router->resource('user', 'UserController');


$router->post('/property/{property}/upload', 'PropertyController@upload');
$router->delete('/property/{property}/removeImages', 'PropertyController@removeAllImages');

$router->resource('agent.client', 'AgentClientController');

$router->resource('property', 'PropertyController');

$router->post('/auth', 'BasicAuthController@Auth');
$router->post('/user/{user}/changePassword', 'UserController@changePassword');

