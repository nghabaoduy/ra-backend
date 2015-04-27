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
}
