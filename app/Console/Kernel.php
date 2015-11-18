<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Property;
use Illuminate\Support\Facades\DB;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use App\Installation;
use Carbon\Carbon;

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

            $properties = Property::where('submit', 'YES')->where("expired_at", "<=", Carbon::now())->where('expired_notify', 0)->get(["id", "agent_id", "project"]);

            if (count($properties) == 0) {
                return;
            }

            $agentList = [];
            $propProjectList = [];
            $propIdList = [];

            foreach ($properties as $property => $propData) {
                $agentList[] = $propData["agent_id"];
                $propProjectList[] = $propData["project"];
                $propIdList[] = $propData["id"];
            }

            $installations = Installation::whereIn("user_id", $agentList)->get();

            $msg = [];

            foreach ($installations as $installation) {
                if ($installation->device_token) {

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
                        'prop_id' => $propId,
                        'alert_type' => 'expired_at'
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

            DB::table('property')->whereIn("id", $propIdList)->update(array('submit' => 'NO'));

        })->everyFiveMinutes();
	}

}
