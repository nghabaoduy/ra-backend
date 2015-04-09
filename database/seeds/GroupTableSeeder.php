<?php
/**
 * Created by PhpStorm.
 * User: duy work
 * Date: 3/28/2015
 * Time: 4:38 PM
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Property;
use App\Group;

class GroupTableSeeder extends Seeder  {

    public function run() {
        $filename = public_path()."/Data/Group.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $groups = $dataArr['results'];

        foreach ($groups as $groupData) {

            $creator = null;
            if (array_key_exists('creator', $groupData) ) {
                $objectId = $groupData['creator']['objectId'];
                $creator = User::where('object_id', $objectId)->first();
            }

            if ($creator) {
                $newUserData = [
                    'creator_id' => $creator->id,
                    'switch1' =>  array_key_exists('switch1', $groupData) ? $groupData['switch1'] : false,
                    'switch2' =>  array_key_exists('switch2', $groupData) ? $groupData['switch2'] : false,
                    'title' =>  array_key_exists('title', $groupData) ? $groupData['title'] : false,
                    'object_id' =>  array_key_exists('objectId', $groupData) ? $groupData['objectId'] : false,
                ];
                Group::create($newUserData);
            }
        }
    }
}