<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\ScheduleEvent;
use App\Http\Requests\CreateScheduleRequest;

class ScheduleEventController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		//
        $all = ScheduleEvent::with('image', 'property', 'requester', 'requester.profileImage', 'receiverAgent','receiverAgent.profileImage', 'property.propertyImage', 'property.agent', 'property.creator', 'property.propertyImages', 'property.agent.profileImage', 'property.creator.profileImage');

        if ($request->has('user_id')) {
            $id = $request->get('user_id');

            //dd($id);
            $all= $all->where('requester_id', $id)->orWhere('receiver_agent_id', $id);
        }

        $all = $all->get();
        //dd($all);
        return response($all);
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
	public function store(CreateScheduleRequest $request)
	{
		//
        $scheduleEvent = ScheduleEvent::create($request->all());

        $scheduleEvent->with('image', 'property', 'requester', 'requester.profileImage', 'receiverAgent','receiverAgent.profileImage', 'property.propertyImage', 'property.agent', 'property.creator', 'property.propertyImages', 'property.agent.profileImage', 'property.creator.profileImage');

        return response($scheduleEvent);
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
        $scheduleEvent = ScheduleEvent::with('image', 'property', 'requester', 'requester.profileImage', 'receiverAgent','receiverAgent.profileImage', 'property.propertyImage', 'property.agent', 'property.creator', 'property.propertyImages', 'property.agent.profileImage', 'property.creator.profileImage')
                                        ->where('id', $id)->first();
        if (!$scheduleEvent)
            return response(json_encode(['message' => 'schedule not found']));
        return response($scheduleEvent);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		//

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//
        $scheduleEvent = ScheduleEvent::with('image', 'property', 'requester', 'requester.profileImage', 'receiverAgent','receiverAgent.profileImage', 'property.propertyImage', 'property.agent', 'property.creator', 'property.propertyImages', 'property.agent.profileImage', 'property.creator.profileImage')
            ->where('id', $id)->first();
        if (!$scheduleEvent)
            return response(json_encode(['message' => 'schedule not found']));

        $scheduleEvent->update($request->all());

        return response($scheduleEvent);
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
        $scheduleEvent = ScheduleEvent::with('image', 'property', 'requester', 'requester.profileImage', 'receiverAgent','receiverAgent.profileImage', 'property.propertyImage', 'property.agent', 'property.creator', 'property.propertyImages', 'property.agent.profileImage', 'property.creator.profileImage')
            ->where('id', $id)->first();
        if (!$scheduleEvent)
            return response(json_encode(['message' => 'schedule not found']));

        $scheduleEvent->delete();

        return response(null, 204);
	}

}
