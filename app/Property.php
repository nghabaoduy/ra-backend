<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model {

	//
    protected $table = 'property';

    protected $fillable = [
        'object_id',
        'address',
        'agent_id',
        'aircon',
        'amps',
        'area',
        'basement',
        'bedroom',
        'blk_no',
        'building_type',
        'car_park',
        'ceiling_height',
        'commercial_type',
        'district',
        'dual_prop_project_id',
        'electrical_load',
        'electrical_value',
        'exhaust',
        'flatted',
        'floor_load',
        'furnishing',
        'garden',
        'grade',
        'grease_trap',
        'ground_level',
        'gst',
        'hdb_type',
        'industry_type',
        'land_size',
        'land_type',
        'level_no',
        'listing_type',
        'main_door_direction',
        'main_gate_facing',
        'no_of_cargo_lift',
        'no_of_storey',
        'own_pantry',
        'property_image_id',
        'post_code',
        'pr_type',
        'price',
        'project',
        'project_name',
        'property_type',
        'psf',
        'rental_yield',
        'rented_price',
        'roof_terrance',
        'room_position',
        'server_room',
        'status',
        'submit',
        'top_year',
        'swimming_pool',
        'tenanted_price',
        'tenure',
        'town',
        'unit_no',
        'valuation',
        'vehicle_access',
        'creator_id',
        'comment',
        'expired_at',
        'expired_notify',
        'expired_notify_3days',
        'contract_expired_at',
        'contract_expired_notify',
        'auto_extend_expired'
    ];

    public function propertyImage() {
        return $this->belongsTo('App\FileAsset', 'property_image_id');
    }

    public function creator() {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function agent() {
        return $this->belongsTo('App\User', 'agent_id');
    }

    public function propertyImages() {
        return $this->belongsToMany('App\FileAsset','property_image', 'property_id', 'file_id');
    }

    public function agentProfileImage() {
        return $this->belongsToMany('App\FileAsset', 'user', 'agent_id', 'profile_image_id');
    }
}
