<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

	//

    protected $table = 'group';

    protected $fillable = [
        'object_id',
        'switch1',
        'switch2',
        'title',
        'creator_id',
    ];

    public function creator() {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function members() {
        return $this->belongsToMany('App\User','group_participation', 'group_id', 'user_id')->withPivot('id', 'is_accept', 'created_at', 'updated_at');
    }
}
