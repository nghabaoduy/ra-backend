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
use App\GroupSharing;
use App\Group;

class GroupSharingTableSeeder extends Seeder  {

    public function run() {
        /*
         * Required Group and Property
         */

        $filename = public_path()."/Data/GroupSharingInfo.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $groupSharing = $dataArr['results'];

        foreach ($groupSharing as $sharingData) {

            $group = null;
            if (array_key_exists('groupId', $sharingData)) {
                $objectId = $sharingData['groupId']['objectId'];
                $group = Group::where('object_id', $objectId)->first();
            }

            $property = null;
            if (array_key_exists('propertyId', $sharingData) ) {
                $objectId = $sharingData['propertyId']['objectId'];
                $property = Property::where('object_id', $objectId)->first();
            }

            if ($group && $property) {
                $newSharingData = [
                    'group_id' => $group->id,
                    'property_id' =>  $property->id,
                    'object_id' =>  array_key_exists('objectId', $sharingData) ? $sharingData['objectId'] : false,
                ];
                GroupSharing::create($newSharingData);
            }
        }
    }
}