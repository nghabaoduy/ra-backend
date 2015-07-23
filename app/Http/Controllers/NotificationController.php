<?php namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Property;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Installation;
use App\Http\Requests\SendAllNotificationRequest;
use App\Http\Requests\SendToNotificationRequest;
use App\Services\NotificationCenter;
use App\GroupParticipation;
use Davibennun\LaravelPushNotification\Facades\PushNotification;

class NotificationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    public function pushAll(SendAllNotificationRequest $request) {
        $appIdentifier = $request->get('app_identifier');
        $isDev = boolval($request->get('is_dev'));
$isDev = false;
        $msg = [];
        $message = $request->get('message');
//($message);
        //dd($isDev);

        $all = Installation::where('app_identifier', $appIdentifier)->get();



        foreach ($all as $installation) {
            if ($installation->device_token) {
                $temp = explode(".",$installation->app_identifier);

                $identifier = $temp[count($temp) - 1];
                $alert = $message["alert"];
                $newMessage = $message;
                unset($newMessage["alert"]);

                $content = PushNotification::Message($alert,$newMessage);
                $result = PushNotification::app($identifier)
                    ->to($installation->device_token)
                    ->send($content);
                if ($result) {
                    $msg[]= "send to ". $installation->id ;
                    sleep(1);
                } else {
                    $msg[]= "Failed send to ". $installation->id ;
                }
            }
        }
        return response(json_encode($msg));
    }

    public function pushTo(SendToNotificationRequest $request) {
        $appIdentifier = $request->get('app_identifier');
        $userId = $request->get('user_id');
        $isDev = boolval($request->get('is_dev'));
        $isDev = false;
        $msg = [];
        $message = $request->get('message');

        if ($request->has('group_sharing') && $request->get('group_sharing') == 'share') {

            $groupId = $message['group_id'];

            $group = Group::with('creator','members', 'creator.profileImage')->where('id', $groupId)->first();

            if ($group && boolval($group->switch2) == true) {
                foreach ($group->members as $member) {
                    $allInstallation = Installation::where('app_identifier', $appIdentifier)->where('user_id', $member->id)->get();
                    foreach ($allInstallation as $installation) {
                        if ($installation->device_token) {



                            $customMessage = [
                                'pushType' => 'group_tab3',
                                'group_id' => $groupId
                            ];

                            $temp = explode(".",$installation->app_identifier);

                            $identifier = $temp[count($temp) - 1];
                            $alert = 'My groups - '.$group->title.': New share listing';

                            $content = PushNotification::Message($alert,$customMessage);
                            $result = PushNotification::app($identifier)
                                ->to($installation->device_token)
                                ->send($content);

                            if ($result) {
                                $msg[]= "send to members ". $installation->id ;
                            } else {
                                $msg[]= "Failed send to members". $installation->id ;
                            }
                        }
                    }
                }
            }
        }
        $all = Installation::where('app_identifier', $appIdentifier)->where('user_id', $userId)->get();

        foreach ($all as $installation) {
            if ($installation->device_token) {



                $temp = explode(".",$installation->app_identifier);

                $identifier = $temp[count($temp) - 1];
                $alert = $message["alert"];
                $newMessage = $message;
                unset($newMessage["alert"]);

                $content = PushNotification::Message($alert,$newMessage);
                $result = PushNotification::app($identifier)
                    ->to($installation->device_token)
                    ->send($content);
                if ($result) {
                    $msg[]= "send to ". $installation->device_token ;
                } else {
                    $msg[]= "Failed send to ". $installation->id ;
                }
            }
        }
        return response(json_encode($msg));
    }


    public function marketNewProperty(Request $request) {
        $appIdentifier = $request->get('app_identifier');
        $userId = $request->get('user_id');
        $isDev = boolval($request->get('is_dev'));
        $isDev = false;

        $msg = [];

        $pushList = [];

        $data = [];
        $allData = Group::with('creator','members', 'creator.profileImage')->where('creator_id', $userId)->get();


        foreach ($allData as $group) {
            $data[] = $group;
        }

        $participations = GroupParticipation::with('group', 'group.creator','group.members', 'group.creator.profileImage')->where('user_id', $userId)->get();

        foreach ($participations as $part) {
            $data[] = $part->group;
        }


        foreach ($data as $group) {
            if (boolval($group->switch2) == true) {

                $msg[]= "Send to Group ". $group->id." is allowed";
                foreach ($group->members as $member) {
                    $allInstallation = Installation::where('app_identifier', $appIdentifier)->where('user_id', $member->id)->get();

                    foreach ($allInstallation as $installation) {

                        if ($installation->device_token && $installation->user_id != $userId) {

                            $pushList[] = ['installation' => $installation, 'group' => $group];
                        }
                    }



                }
                $allInstallation = Installation::where('app_identifier', $appIdentifier)->where('user_id', $group->creator_id)->get();
                foreach ($allInstallation as $installation) {

                    if ($installation->device_token && $installation->user_id != $userId) {

                        //$pushList[] = ['installation' => $installation, 'group' => $group];
                        $pushList[] = ['installation' => $installation, 'group' => $group];
                    }
                }

            } else {
                $msg[]= "Group ". $group->id." is not allowed";
            }
        }

        foreach ($pushList as $myPush) {
            $customMessage = [
                'pushType' => 'group_tab3',
                'group_id' => $myPush['group']->id
            ];
            $temp = explode(".",$myPush['installation']->app_identifier);

            $identifier = $temp[count($temp) - 1];
            $alert = 'My groups - '.$myPush['group']->title.': New share listing';

            $content = PushNotification::Message($alert,$customMessage);
            $result = PushNotification::app($identifier)
                ->to($myPush['installation']->device_token)
                ->send($content);
            if ($result) {
                $msg[]= "send to members ". $myPush['installation']->id ;
                sleep(1);
            } else {
                $msg[]= "Failed send to members". $myPush['installation']->id ;
            }
        }


        return response(json_encode($msg));
    }

}
