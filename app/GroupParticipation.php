<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupParticipation extends Model {

	//
    protected $table = 'group_participation';

    protected $fillable = [
        'object_id',
        'is_accept',
        'user_id',
        'group_id'
    ];
}
