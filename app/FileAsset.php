<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Filesystem\Cloud;

class FileAsset extends Model {

	//
    protected $table = 'file';

    protected $fillable = [
        'name',
        'url',
        'type',
        'source'];
}
