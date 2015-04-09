<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model {

	//
    protected $table = 'installation';

    protected $fillable = [
        'object_id',
        'appName',
        'app_identifier',
        'app_version',
        'device_token',
        'time_zone',
        'user_id'
    ];

}
