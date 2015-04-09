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
use App\Installation;

class InstallationTableSeeder extends Seeder  {

    public function run() {
        $filename = public_path()."/Data/_Installation.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $installs = $dataArr['results'];

        foreach ($installs as $installData) {

            $user = null;
            if (array_key_exists('user', $installData)) {
                $objectId = $installData['user']['objectId'];
                $user = User::where('object_id', $objectId)->first();
            }

            $newInstallData = [
                'object_id' => array_key_exists('objectId', $installData) ? $installData['objectId']: NULL,
                'appName' => array_key_exists('appName', $installData) ? $installData['appName']: NULL,
                'app_identifier' => array_key_exists('appIdentifier', $installData) ? $installData['appIdentifier']: NULL,
                'app_version' =>  array_key_exists('appVersion', $installData) ? $installData['appVersion'] : 0,
                'device_token' => array_key_exists('deviceToken', $installData) ? $installData['deviceToken'] : NULL,
                'time_zone' => array_key_exists('timeZone', $installData) ? $installData['timeZone'] : NULL,
                'user_id' => $user ? $user->id : NULL,
            ];


            Installation::create($newInstallData);
        }



    }
}