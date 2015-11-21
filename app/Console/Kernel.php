<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Property;
use Illuminate\Support\Facades\DB;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use App\Installation;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

        $schedule->call(function(){
            // expired at
            $properties = Property::where('submit', 'YES')->where("expired_at", "<=", Carbon::now()->toDateTimeString())->get(["id", "agent_id", "project"]);

            if (count($properties) > 0) {
                $agentList = [];
                $propProjectList = [];
                $propIdList = [];

                foreach ($properties as $property => $propData) {
                    $agentList[] = $propData["agent_id"];
                    $propProjectList[] = $propData["project"];
                    $propIdList[] = $propData["id"];
                }

                DB::table('property')->whereIn("id", $propIdList)->update(array('submit' => 'NO'));

                $installations = Installation::whereIn("user_id", $agentList)->get();

                $msg = [];

                foreach ($installations as $installation) {
                    if ($installation->device_token && $installation->app_identifier == "sg.com.hvsolutions.realJamesGoh") {

                        $propId = "N/A";
                        $propProject = "N/A";

                        $index = 0;
                        foreach ($agentList as $agent) {
                            if ($agent == $installation->user_id) {
                                $propId = $propIdList[$index];
                                $propProject = $propProjectList[$index];
                                break;
                            }
                            $index++;
                        }

                        $temp = explode(".", $installation->app_identifier);

                        $identifier = $temp[count($temp) - 1];

                        if ($propProject == "N/A") {
                            $alert = 'Property id:'.$propId.' has expired. Do you want to market it again?';
                        } else {
                            $alert = 'Property "'.$propProject.'" has expired. Do you want to market it again?';
                        }


                        $content = PushNotification::Message($alert, [
                            'badge' => 1,
                            'custom' => array("prop_id"=>$propId,"pushType"=>"expired_at")
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
        })->everyFiveMinutes();

        $schedule->call(function(){
            // expired at 3 days
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
                            'custom' => array("prop_id"=>$propId,"pushType"=>"expired_at_3days")
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
        })->everyFiveMinutes();


        $schedule->call(function(){

            // contract expired day
            $properties = Property::where('submit', 'NO')->where('contract_expired_notify', true)->where("contract_expired_at", "<=", Carbon::now()->toDateTimeString())->get(["id", "agent_id", "project"]);

            if (count($properties) > 0) {
                $agentList = [];
                $propProjectList = [];
                $propIdList = [];


                foreach ($properties as $property => $propData) {
                    $agentList[] = $propData["agent_id"];
                    $propProjectList[] = $propData["project"];
                    $propIdList[] = $propData["id"];
                }

                DB::table('property')->whereIn("id", $propIdList)->update(array('submit' => 'NO' , 'contract_expired_notify' => false));


                $installations = Installation::whereIn("user_id", $agentList)->get();

                $msg = [];

                foreach ($installations as $installation) {
                    if ($installation->device_token && $installation->app_identifier == "sg.com.hvsolutions.realJamesGoh") {

                        $propId = "N/A";
                        $propProject = "N/A";

                        $index = 0;
                        foreach ($agentList as $agent) {
                            if ($agent == $installation->user_id) {
                                $propId = $propIdList[$index];
                                $propProject = $propProjectList[$index];
                                break;
                            }
                            $index++;
                        }

                        $temp = explode(".", $installation->app_identifier);

                        $identifier = $temp[count($temp) - 1];

                        if ($propProject == "N/A") {
                            $alert = 'Contract of Property id:'.$propId.' has expired. Do you want to extend it?';
                        } else {
                            $alert = 'Contract of property "'.$propProject.'" has expired. Do you want to extend it?';
                        }


                        $content = PushNotification::Message($alert, [
                            'badge' => 1,
                            'custom' => array("prop_id"=>$propId,"pushType"=>"contract_expired")
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


        })->everyFiveMinutes();
	}

}
