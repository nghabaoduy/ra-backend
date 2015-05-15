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
use DateTime;
use Illuminate\Support\Facades\File;
use App\FileAsset;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $query = User::with("profileImage");
        if ($request->has('user_type')) {
            $query = $query->where('user_type', $request->get('user_type'));
        }
        $query = $query->get();
        return response($query);
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

    public function uploadProfile($id, Request $request, Cloud $cloud) {
        $user = User::where('id', $id)->first();
        if (!$user)
            return response(json_encode(['message' => 'user not found']), 404);
        $file = $request->file('image');

        if ($file) {
            $now = new DateTime();
            $randomString = $this->quickRandom(6);
            $fileName = 'image-'.$randomString.'-'.$now->getTimestamp().'.'.$file->getClientOriginalExtension();
            $success = $cloud->put($fileName, File::get($file) );

            if ($success) {
                $url = env('S3_URL').$fileName;
                $newAsset = [
                    'name' => $fileName,
                    'url' => $url,
                    'type' => 'img',
                    'source' => 'aws-s3'
                ];
                $paths[] = $url;
                $asset = FileAsset::create($newAsset);

                $user->profile_image_id = $asset->id;
                $user->update();
            }
        }

        return response(json_encode(['url' => $url]), 200);
    }

    public function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
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

    public function forgotPassword(ForgotPasswordRequest $request) {
        $username = $request->get('username');

        $user = User::where('username', $username)->first();

        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }

        $newPassword = $this->quickRandom(6);

        $user->password = bcrypt($newPassword);
        $user->update();

        Mail::send('emails.forgotPassword', ['password' => $newPassword], function($message) use ($user)
        {
            $message->to($user->email);
        });

        return response(null, 204);
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
