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

class UserTableSeeder extends Seeder  {

    public function run() {
        $filename = public_path()."/Data/User.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $users = $dataArr['results'];

        foreach ($users as $userData) {

            $icon = null;
            if (array_key_exists("icon", $userData)) {
                $iconData = $userData['icon'];

                $newIconData = [
                    "type" => $iconData["__type"],
                    "name" => $iconData["name"],
                    "url" => $iconData["url"],
                    "source" => "Parse.com"
                ];

                $icon = \App\FileAsset::create($newIconData);
            }


            $newUserData = [
                'username' => $userData['username'],
                'agent_phone' => array_key_exists('agentPhone', $userData) ? $userData['agentPhone']: NULL,
                'email' =>  array_key_exists('email', $userData) ? $userData['email'] : NULL,
                'first_name' => array_key_exists('firstName', $userData) ? $userData['firstName'] : NULL,
                'last_name' => array_key_exists('firstName', $userData) ? $userData['lastName'] : NULL,
                'object_id' => $userData['objectId'],
                'phone' => array_key_exists('phone', $userData) ? $userData['phone'] : NULL,
                'user_type' => array_key_exists('userType', $userData) ? $userData['userType'] : NULL,
                'address' => array_key_exists('address', $userData) ? $userData['address'] : NULL,
                'profile_image_id' => $icon? $icon->id : NULL,
            ];


            User::create($newUserData);
        }



    }
}