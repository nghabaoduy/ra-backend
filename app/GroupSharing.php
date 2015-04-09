<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupSharing extends Model {

	//
    protected $table = 'group_sharing';

    protected $fillable = [
        'object_id',
        'property_id',
        'group_id'
    ];

}
