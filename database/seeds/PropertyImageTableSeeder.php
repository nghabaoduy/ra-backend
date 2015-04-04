<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\FileAsset;
class PropertyImageTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');

        $filename = public_path()."/Data/Photo.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $propertieImages = $dataArr['results'];

        foreach ($propertieImages as $imageData) {
            $pictureData = null;
            if (array_key_exists('photo', $imageData)) {
                $pictureData = $imageData['photo'];
            }

            $property = null;

            if (array_key_exists('property', $imageData)) {
                $propertyData = $imageData['property'];
                $property = \App\Property::where('object_id',$propertyData["objectId"])->first();
            }

            if ($property != null && $pictureData != null) {

                $newPicture = [
                    "type" => $pictureData["__type"],
                    "name" => $pictureData["name"],
                    "url" => $pictureData["url"],
                    "source" => "Parse.com"
                ];
                $picture = FileAsset::create($newPicture);

                $newData = [
                    'file_id' => $picture->id,
                    'property_id' => $property->id
                ];

                \App\PropertyImage::create($newData);
            }
        }
    }

}