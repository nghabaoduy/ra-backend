<?php namespace App\Http\Controllers;

use App\AgentClient;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;


class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        return response(User::with("profileImage")->get());
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
        $user = User::with("profileImage")->where('id', $id)->first();

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

        $user = User::with("profileImage")->where('id', $id)->first();

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

        $user->update($updateData);

        return response($user);
	}

    public function changePassword($id,ChangePasswordRequest $request) {
        $user = User::with("profileImage")->where('id', $id)->first();

        if (!$user)
            response(json_encode(['message' => 'user not found']), 404);

        $oldPassword = $request->get('old_password');
        $newPassword = $request->get('new_password');


        if (Hash::check($oldPassword, $user->password)) {
            $user->password = bcrypt($newPassword);
            $user->save();
        } else {
            return response(json_encode(['message' => 'invalid credentials']), 500);
        }

        return response(null, 204);
    }

    public  function  uploadProfile() {

    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Cloud $fileSystem, Request $request)
	{
		//
        /*
        $user = User::find($id);
        if (!$user)
            response(json_encode(['message' => 'user not found']), 404);

        if ($user->profileImage) {
            $fileSrc = $user->profileImage->source;

            if ($fileSrc == "aws-s3" && $fileSystem->exists($user->profileImage->name)) {
                $fileSystem->delete($user->profileImage->name);
            }

            $user->profileImage->delete();
        }


        $user->delete();*/



        return response(null, 204);
	}

}
