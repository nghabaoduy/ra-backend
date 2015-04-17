<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;


class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        return response(User::search('client')->with("profileImage")->get());
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
	public function store(CreateUserRequest $request)
	{
		//
        $data = $request->all();
        $newUser = User::create($data);
        return response($newUser);
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
        $user = User::find($id);

        if (!$user)
            response(json_encode(['message' => 'user not found']), 404);
        return response($user);
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
	public function update($id, UpdateUserRequest $request)
	{
		//
        //dd($request->all());

        $user = User::find($id);

        if (!$user)
            response(json_encode(['message' => 'user not found']), 404);

        $updateData = $request->all();


        if ($request->has('username')) {
            unset($updateData['username']);
        }


        if ($request->has('phone')) {
            unset($updateData['phone']);
        }

        if ($request->has('password') && $request->get('password') != '') {
            $updateData['password'] = bcrypt($updateData['password']);
        } else {
            unset($updateData['password']);
        }


        dd($updateData);
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

        $user = User::find($id);
        $user->delete();
        response(null, 204);
	}

}
