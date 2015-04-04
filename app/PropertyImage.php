<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model {

	//
    protected $table = 'property_image';

    protected $fillable = [
        'property_id',
        'file_id'
    ];
}
