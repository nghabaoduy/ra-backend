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
use App\UserFavorite;
use App\Group;
use App\GroupParticipation;

class GroupParticipationTableSeeder extends Seeder  {

    public function run() {
        $filename = public_path()."/Data/GroupParticipants.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $participants = $dataArr['results'];

        foreach ($participants as $participantData) {

            $user = null;
            if (array_key_exists('userId', $participantData) ) {
                $objectId = $participantData['userId']['objectId'];
                $user = User::where('object_id', $objectId)->first();
            }

            $group = null;
            if (array_key_exists('groupId', $participantData)) {
                $objectId = $participantData['groupId']['objectId'];
                $group = Group::where('object_id', $objectId)->first();
            }

            if ($user && $group) {
                $newUserData = [
                    'user_id' => $user->id,
                    'group_id' =>  $group->id,
                    'object_id' =>  array_key_exists('objectId', $participantData) ? $participantData['objectId'] : false,
                    'is_accept' =>  array_key_exists('isAccept', $participantData) ? $participantData['isAccept'] : false,
                ];
                GroupParticipation::create($newUserData);
            }
        }
    }
}