<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model {

	//
    protected $table = 'schedule_event';
    protected $fillable = [
        'object_id',
        'agent_accept',
        'requester_accept',
        'date_time',
        'is_reschedule',
        'meetup_revenue',
        'project_name',
        'project_type',
        'image_id',
        'property_id',
        'requester_id',
        'receiver_agent_id'
    ];


    public function image() {
        return $this->belongsTo('App\FileAsset', 'image_id');
    }

    public function property() {
        return $this->belongsTo('App\Property', 'property_id');
    }

    public function requester() {
        return $this->belongsTo('App\User', 'requester_id');
    }

    public function receiverAgent() {
        return $this->belongsTo('App\User', 'receiver_agent_id');
    }
}
