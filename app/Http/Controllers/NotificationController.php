<?php namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Installation;
use App\Http\Requests\SendAllNotificationRequest;
use App\Http\Requests\SendToNotificationRequest;

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

        $msg = [];
        $message = $request->get('message');
//($message);
        //dd($isDev);

        $all = Installation::where('app_identifier', $appIdentifier)->get();



        foreach ($all as $installation) {
            if ($installation->device_token) {
                $push = new NotificationService($installation->app_identifier, $installation->device_token, $isDev);
                $result = $push->sendPush($message);
                if ($result) {
                    $msg[]= "send to ". $installation->id ;
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
        $msg = [];
        $message = $request->get('message');


        if ($request->has('group_sharing') && $request->get('group_sharing') == 'share') {

            $groupId = $message['group_id'];

            $group = Group::with('creator','members', 'creator.profileImage')->where('id', $groupId)->first();

            if ($group && $group->switch2 === true) {
                foreach ($group->members as $member) {
                    $allInstallation = Installation::where('app_identifier', $appIdentifier)->where('user_id', $member->id)->get();
                    foreach ($allInstallation as $installation) {
                        if ($installation->device_token) {
                            $push = new NotificationService($installation->app_identifier, $installation->device_token, $isDev);

                            $customMessage = [
                                'alert' => 'My groups - '.$group->title.': New share listing',
                                'pushType' => 'group_tab3',
                                'group_id' => $groupId
                            ];
                            $result = $push->sendPush($customMessage);
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



//($message);
        //dd($isDev);

        $all = Installation::where('app_identifier', $appIdentifier)->where('user_id', $userId)->get();
            //dd($all);
        $msg = [];

        foreach ($all as $installation) {
            if ($installation->device_token) {
                $push = new NotificationService($installation->app_identifier, $installation->device_token, $isDev);
                $result = $push->sendPush($message);
                if ($result) {
                    $msg[]= "send to ". $installation->id ;
                } else {
                    $msg[]= "Failed send to ". $installation->id ;
                }
            }
        }


        return response(json_encode($msg));
    }

}
