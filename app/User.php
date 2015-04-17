<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\FileAsset;
use Nicolaslopezj\Searchable\SearchableTrait as SearchableTraithableTrait;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;
    use SearchableTraithableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'username',
        'email',
        'password',
        'object_id',
        'first_name',
        'last_name',
        'phone',
        'user_type',
        'agent_phone',
        'phone',
        'profile_image_id'
    ];

    protected $searchable = [
        'columns' => [
            'user_type' => 10,
        ]
    ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];

    public function profileImage() {
        return $this->belongsTo('App\FileAsset', "profile_image_id");
    }

    public function clients() {
        return $this->belongsToMany('App\User', 'agent_client', 'agent_id', 'client_id','profileImage');
    }



}
