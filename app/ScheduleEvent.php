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
}
