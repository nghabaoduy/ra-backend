<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model {

	//
    protected $table = 'partner';
    protected $fillable = [
        'object_id',
        'address',
        'bank',
        'company',
        'contact',
        'email',
        'name',
        'type',
        'creator_id',
    ];
}
