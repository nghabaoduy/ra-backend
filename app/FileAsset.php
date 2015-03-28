<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FileAsset extends Model {

	//
    protected $table = 'file';

    protected $fillable = [
        'name',
        'url',
        'type',
        'source'];

}
