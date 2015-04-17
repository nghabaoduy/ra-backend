<?php namespace App\Http\Controllers;

use App\AgentClient;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class AgentClientController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(User $agent, Request $request)
	{
		//
        //dd($agent->toJson());

        if  ($agent->user_type == 'client') {
            return response(json_encode(['message' => 'Current user is not agent']), 400);
        }

        $data = AgentClient::with('client', 'client.profileImage')->where('agent_id', $agent->id)->get();


        $newData = [];

        foreach($data as $agentClient) {
            $newData[] = $agentClient->client;
        }


        //dd($data);

        return response(json_encode($newData) );
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
