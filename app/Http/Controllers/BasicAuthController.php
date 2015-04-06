<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthRequest;

class BasicAuthController extends Controller {

    public function Auth(AuthRequest $request)
    {
        if (Auth::attempt(['username' => $request->get('username'), 'password' => $request->get('password')]))
        {
            $id = Auth::user()->id;
            $user = User::with('profileImage')->find($id);
            return response($user);
        }
        return response(json_encode(["message" => "Invalid credentials."]), 400);
    }

    public function ChangePassword() {

    }

    public function ChangeProfileImage() {

    }
}
