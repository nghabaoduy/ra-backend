<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        if ($this->has('password')) {
                $this->merge(['password' => bcrypt($this->getPassword())]);
        }
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
            'username' => 'required|unique:user,username',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required',
            'user_type' => 'required|in:agent,client',
		];
	}

}
