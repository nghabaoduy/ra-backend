<?php namespace App\Http\Controllers;

use App\GroupSharing;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Group;
use App\Http\Requests\GetGroupRequest;
use App\Http\Requests\CreateGroupRequest;
use App\GroupParticipation;
use Illuminate\Http\Response;

class GroupController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(GetGroupRequest $request)
	{
		//

        if ($request->has('user_id') && $request->get('user_id') != "") {
            $data = [];
            $allData = Group::with('creator','members', 'creator.profileImage')->where('creator_id', $request->get('user_id'))->get();


            foreach ($allData as $group) {
                $data["is_creator"][] = $group;
            }

            $participations = GroupParticipation::with('group', 'group.creator','group.members', 'group.creator.profileImage')->where('user_id', $request->get('user_id'))->get();

            foreach ($participations as $part) {
                $data['is_members'] = $part->group;
            }

            $all = json_encode($data);
        } else {
            $all = Group::with('creator','members', 'creator.profileImage')->get();
        }


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
	public function store(CreateGroupRequest $request)
	{
        //
        $new = Group::create($request->all());
        $new = Group::with('creator', 'creator.profileImage')->where('id', $new->id)->first();
        return response($new);
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
        $new = Group::with('creator','members', 'creator.profileImage')->where('id', $id)->first();
        if (!$new)
            return response(json_encode(['message' => 'group not found']));

        return response($new);
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
	public function update($id, Request $request)
    {	//
        $new = Group::with('creator','members', 'creator.profileImage')->where('id', $id)->first();
        if (!$new)
            return response(json_encode(['message' => 'group not found']));
        $new->update($request->all());
        return response($new);
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
        $new = Group::where('id', $id)->first();
        if (!$new)
            return response(json_encode(['message' => 'group not found']));
        $new->delete();
        return response(null, 204);
	}

}
