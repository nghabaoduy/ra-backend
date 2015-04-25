<?php namespace App\Http\Controllers;

use App\AgentClient;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateAgentClientRequest;

class AgentClientController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(User $agent, Request $request)
	{
        if  ($agent->user_type == 'client') {
            return response(json_encode(['message' => 'Current user is not agent']), 400);
        }

        $data = AgentClient::with('client', 'client.profileImage')->where('agent_id', $agent->id)->get();
        $newData = [];

        foreach($data as $agentClient) {
            $newData[] = $agentClient->client;
        }
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
	public function store(User $agent, CreateAgentClientRequest $request)
	{
		//
        $username = $request->get('username');
        $user = User::where('username', $username)->first();
        $data = $request->all();

        if ($request->get('password') == "" || !$request->has('password')) {
            $data['password'] = bcrypt($request->get('username'));
        }
        if (!$user) {
            $user = User::create($data);
        } else {
            $user->update($data);
        }

        $agentClient = AgentClient::with('agent', 'client')->where('client_id', $user->id)->where("agent_id", $agent->id)->first();

        if ($agentClient) {
            return response($agentClient);
        }

        $newAgentClient = [
            'agent_id' => $agent->id,
            'client_id' => $user->id,
        ];
        $new = AgentClient::create($newAgentClient);

        $data = AgentClient::where('id', $new->id)->with('agent', 'client')->first();
        return response($data);
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
	public function destroy(User $agent, User $client, Request $request)
	{
		//
        $agentClient = AgentClient::where('client_id', $client->id)->where("agent_id", $agent->id)->first();

        if (!$agentClient)
            return response(json_encode(['message'=> 'agent client not found']));
        $agentClient->delete();

        return response(null, 204);
	}
}
