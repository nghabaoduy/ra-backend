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
$router->resource('schedule', 'ScheduleEventController');
$router->get('/projectList', 'PropertyController@getProjectList');

$router->resource('agent.client', 'AgentClientController');

$router->resource('property', 'PropertyController');

$router->resource('group', 'GroupController');
$router->resource('group.participation', 'GroupParticipationController');
$router->resource('group.sharing', 'GroupSharingController');


$router->resource('installation', 'InstallationController');
$router->resource('partner', 'PartnerController');
$router->resource('favorite', 'FavoriteController');
$router->post('/unfavorite', 'FavoriteController@unfavorite');
$router->post('/auth', 'BasicAuthController@Auth');
$router->post('/user/{user}/changePassword', 'UserController@changePassword');
$router->post('/user/{user}/uploadProfile', 'UserController@uploadProfile');

use App\Services\NotificationService;
$router->get('/testing', function() {
    $push = new NotificationService('sg.com.hvsolutions.realJamesGoh', 'ecc87ee370ebc1aabc9301ad670e4ada4d34a4424216f2431f11d893a0fa3ec3');

    //dd($scheduleEvent);

    $data = ['alert' => 'abc' , 'pushType' => 'group_tab3', 'group_id' => 1];
    //dd($data);
    $result = $push->sendPush($data);
    return response(json_encode($data));
});


$router->post('/pushAll', 'NotificationController@pushAll');
$router->post('/pushTo', 'NotificationController@pushTo');
$router->post('/marketNewProperty', 'NotificationController@marketNewProperty');

$router->post('/forgotPassword', 'UserController@forgotPassword');
