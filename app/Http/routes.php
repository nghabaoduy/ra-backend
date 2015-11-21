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

use Davibennun\LaravelPushNotification\Facades\PushNotification;
use App\Property;
use App\Installation;
use Carbon\Carbon;

$router->get('/testing', function(\Illuminate\Contracts\Filesystem\Filesystem $filesystem) {

    //dd(public_path() . "/Push/sg.com.hvsolutions.realJamesGoh/Push.pem");
    //$abc = $filesystem->exists(public_path() . "/Push/sg.com.hvsolutions.realJamesGoh/Push.pem");
    //dd($abc);
//    PushNotification::app('realJamesGoh')
//        ->to('ecc87ee370ebc1aabc9301ad670e4ada4d34a4424216f2431f11d893a0fa3ec3')
//        ->send('Hello World, i`m a push message');
//


    //$push = new NotificationService('sg.com.hvsolutions.realJamesGoh', 'ecc87ee370ebc1aabc9301ad670e4ada4d34a4424216f2431f11d893a0fa3ec3');

    //dd($scheduleEvent);

    //$data = ['alert' => 'abc' , 'pushType' => 'group_tab3', 'group_id' => 1];
    //dd($data);
    //$result = $push->sendPush($data);
//    dd('here');
    $properties = Property::where('submit', 'YES')->where("expired_at", "<=", Carbon::now()->addDay(3)->toDateTimeString())->where('expired_notify', 1)->get(["id", "agent_id", "project", "expired_at"]);
    $msg = [];
    if (count($properties) > 0) {
        $agentList = [];
        $propProjectList = [];
        $propIdList = [];
        $expiredAtList = [];

        foreach ($properties as $property => $propData) {
            $agentList[] = $propData["agent_id"];
            $propProjectList[] = $propData["project"];
            $propIdList[] = $propData["id"];
            $expiredAtList[] = $propData["expired_at"];
        }

        DB::table('property')->whereIn("id", $propIdList)->update(array('expired_notify' => 0));

        $installations = Installation::whereIn("user_id", $agentList)->get();



        foreach ($installations as $installation) {
            if ($installation->device_token && $installation->app_identifier == "sg.com.hvsolutions.realJamesGoh") {

                $propId = "N/A";
                $propProject = "N/A";
                $expiredDate = "N/A";

                $index = 0;
                foreach ($agentList as $agent) {
                    if ($agent == $installation->user_id) {
                        $propId = $propIdList[$index];
                        $propProject = $propProjectList[$index];
                        $expiredDate = $expiredAtList[$index];
                        break;
                    }
                    $index++;
                }

                $temp = explode(".", $installation->app_identifier);

                $identifier = $temp[count($temp) - 1];

                if ($propProject == "N/A") {
                    $alert = 'Property id:'.$propId.' will be expired on '.$expiredDate.'.';
                } else {
                    $alert = 'Property "'.$propProject.'" will be expired on '.$expiredDate.'.';
                }


                $content = PushNotification::Message($alert, [
                    'badge' => 1,
                    'prop_id' => $propId,
                    'pushType' => 'expired_at_3days'
                ]);
                $result = PushNotification::app($identifier)
                    ->to($installation->device_token)
                    ->send($content);
                if ($result) {
                    $msg[] = "send to " . $installation->id;
                    sleep(1);
                } else {
                    $msg[] = "Failed send to " . $installation->id;
                }
            }
        }
    }



    return response(json_encode($msg));
});


$router->post('/pushAll', 'NotificationController@pushAll');
$router->post('/pushTo', 'NotificationController@pushTo');
$router->post('/marketNewProperty', 'NotificationController@marketNewProperty');

$router->post('/forgotPassword', 'UserController@forgotPassword');
