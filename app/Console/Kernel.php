<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Property;

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
//		$schedule->command('inspire')
//				 ->hourly();
//
//        $schedule->call(function(){
//
//            $data = Booking::with('foodOrders', 'user.installationList')
//                ->whereBetween('time_slot', [Carbon::now()->toDateTimeString(), Carbon::now()->addHour(0.5)->toDateTimeString()])
//                ->where('is_paid', false)
//                ->where('is_confirmed', false)->get();
//
//
//            $properties = Property::with("agent.installation");
//
//
//            foreach ($data as $booking) {
//                foreach ($booking->user->installationList as $installation) {
//                    PushNotification::app('appNameIOS')
//                        ->to($installation->token)
//                        ->send('You have up coming booking at '.$booking->time_slot.'.Please check your booking list and confirm it');
//                }
//            }
//
//        })->everyFiveMinutes();
	}

}
