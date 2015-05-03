<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Property;
use App\Group;
use App\GroupParticipation;

class GroupSharingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id, Request $request)
	{
        $group = Group::with('creator','members', 'creator.profileImage')->where('id', $id)->first();
        if (!$group)
            return response(json_encode(['message' => 'group not found']));

        $take = 100;
        $skip = 0;

        if ($request->has('take')) {
            $take = $request->get('take');
        }

        if ($request->has('skip')) {
            $skip = $request->get('skip');
        }
		//
        $data = [];


        $data[] = $group->creator_id;
        foreach ($group->members as $member) {
            $data[] = $member->id;
        }
        $properties = Property::with(['propertyImage', 'agent', 'creator', 'propertyImages', 'agent.profileImage', 'creator.profileImage'])->whereIn('agent_id', $data)->where('submit', 'YES')->skip($skip)->take($take)->get();

        return response($properties);
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

}
