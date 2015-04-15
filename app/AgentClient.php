<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentClient extends Model {

	//
    protected $table = 'agent_client';

    protected $fillable = [
        'object_id',
        'agent_id',
        'client_id',
    ];


    public function client() {
        return $this->belongsTo('App\User', 'client_id');
    }

}
