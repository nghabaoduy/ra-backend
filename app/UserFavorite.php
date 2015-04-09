<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model {

	//
    protected $table = 'user_favorite';

    protected $fillable = [
        'user_id',
        'property_id',
    ];
}
