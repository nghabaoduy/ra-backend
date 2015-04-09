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

class UserFavoriteTableSeeder extends Seeder  {

    public function run() {
        $filename = public_path()."/Data/UserFavorite.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $favorites = $dataArr['results'];

        foreach ($favorites as $favoriteData) {

            $user = null;
            if (array_key_exists('user', $favoriteData) ) {
                $objectId = $favoriteData['user']['objectId'];
                $user = User::where('object_id', $objectId)->first();
            }

            $property = null;
            if (array_key_exists('property', $favoriteData) ) {
                $objectId = $favoriteData['property']['objectId'];
                $property = Property::where('object_id', $objectId)->first();
            }

            if ($user && $property) {
                $newUserData = [
                    'user_id' => $user->id,
                    'property_id' =>  $property->id,
                ];
                UserFavorite::create($newUserData);
            }
        }
    }
}