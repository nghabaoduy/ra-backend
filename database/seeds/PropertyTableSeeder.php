<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\User;
use App\FileAsset;
use Illuminate\Database\Eloquent\Model;
use App\Property;
class PropertyTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');

        $filename = public_path()."/Data/Property.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $properties = $dataArr['results'];


        foreach ($properties as $propertyData) {

            $agent = null;
            if (array_key_exists('agent', $propertyData)) {
                $objectId = $propertyData['agent']['objectId'];
                $agent = User::where('object_id', $objectId)->first();
            }

            $picture = null;
            if (array_key_exists('picture', $propertyData)) {
                $pictureData = $propertyData['picture'];

                $newPicture = [
                    "type" => $pictureData["__type"],
                    "name" => $pictureData["name"],
                    "url" => $pictureData["url"],
                    "source" => "Parse.com"
                ];

                $picture = FileAsset::create($newPicture);
            }

            $creator = null;
            if (array_key_exists('userAdded', $propertyData)) {
                $objectId = $propertyData['userAdded']['objectId'];
                $creator = User::where('object_id', $objectId)->first();
            }

            $dualProp = null;

            if (array_key_exists('dualPropObject', $propertyData)) {
                $dualProp = Property::where('object_id', $propertyData['dualPropObject']['objectId'])->first();
            }

            $newPro = [
                'object_id' => array_key_exists('objectId', $propertyData) ? $propertyData['objectId'] : NULL,
                'address' => array_key_exists('address', $propertyData) ? $propertyData['address'] : NULL,
                'agent_id' => $agent? $agent->id : '1',
                'aircon' => array_key_exists('aircon', $propertyData) ? $propertyData['aircon'] : NULL,
                'amps' => array_key_exists('amps', $propertyData) ? $propertyData['amps'] : NULL,
                'area' => array_key_exists('area', $propertyData) ? $propertyData['area'] : NULL,
                'basement' => array_key_exists('basement', $propertyData) ? $propertyData['basement'] : NULL,
                'bedroom'=> array_key_exists('bedroom', $propertyData) ? $propertyData['bedroom'] : NULL,
                'blk_no'=> array_key_exists('blkNo', $propertyData) ? $propertyData['blkNo'] : NULL,
                'building_type'=> array_key_exists('buildingType', $propertyData) ? $propertyData['buildingType'] : NULL,
                'car_park'=> array_key_exists('carPark', $propertyData) ? $propertyData['carPark'] : NULL,
                'ceiling_height'=> array_key_exists('ceilingHeight', $propertyData) ? $propertyData['ceilingHeight'] : 0,
                'commercial_type'=> array_key_exists('commercialType', $propertyData) ? $propertyData['commercialType'] : NULL,
                'district'=> array_key_exists('district', $propertyData) ? $propertyData['district'] : NULL,
                'dual_prop_project_id'=> $dualProp? $dualProp->id : NULL,
                'electrical_load'=> array_key_exists('electricalLoad', $propertyData) ? $propertyData['electricalLoad'] : NULL,
                'electrical_value'=> array_key_exists('electricalValue', $propertyData) ? $propertyData['electricalValue'] : 0,
                'exhaust'=> array_key_exists('exhaust', $propertyData) ? $propertyData['exhaust'] : NULL,
                'flatted'=> array_key_exists('flatted', $propertyData) ? $propertyData['flatted'] : NULL,
                'floor_load'=> array_key_exists('floorLoad', $propertyData) ? $propertyData['floorLoad'] : 0,
                'furnishing'=> array_key_exists('furnishing', $propertyData) ? $propertyData['furnishing'] : NULL,
                'garden'=> array_key_exists('garden', $propertyData) ? $propertyData['garden'] : NULL,
                'grade'=> array_key_exists('grade', $propertyData) ? $propertyData['grade'] : NULL,
                'grease_trap'=> array_key_exists('greaseTrap', $propertyData) ? $propertyData['greaseTrap'] : NULL,
                'ground_level'=> array_key_exists('groundLevel', $propertyData) ? $propertyData['groundLevel'] : NULL,
                'gst'=> array_key_exists('gst', $propertyData) ? $propertyData['gst'] : NULL,
                'hdb_type'=> array_key_exists('hdbType', $propertyData) ? $propertyData['hdbType'] : NULL,
                'industry_type'=> array_key_exists('industryType', $propertyData) ? $propertyData['industryType'] : NULL,
                'land_size'=> array_key_exists('landSize', $propertyData) ? $propertyData['landSize'] : 0,
                'land_type'=> array_key_exists('landType', $propertyData) ? $propertyData['landType'] : NULL,
                'level_no'=> array_key_exists('levelNo', $propertyData) ? $propertyData['levelNo'] : NULL,
                'listing_type'=> array_key_exists('listingType', $propertyData) ? $propertyData['listingType'] : NULL,
                'main_door_direction'=> array_key_exists('mainDoorDirection', $propertyData) ? $propertyData['mainDoorDirection'] : NULL,
                'main_gate_facing'=> array_key_exists('mainGateFacing', $propertyData) ? $propertyData['mainGateFacing'] : NULL,
                'no_of_cargo_lift'=> array_key_exists('noOfCargoLift', $propertyData) ? $propertyData['noOfCargoLift'] : 0,
                'no_of_storey'=> array_key_exists('noOfStorey', $propertyData) ? $propertyData['noOfStorey'] : 0,
                'own_pantry'=> array_key_exists('ownPantry', $propertyData) ? $propertyData['ownPantry'] : NULL,
                'property_image_id'=> $picture? $picture->id : NULL,
                'post_code'=> array_key_exists('postcode', $propertyData) ? $propertyData['postcode'] : NULL,
                'pr_type'=> array_key_exists('prType', $propertyData) ? $propertyData['prType'] : NULL,
                'price'=> array_key_exists('price', $propertyData) ? $propertyData['price'] : 0,
                'project'=> array_key_exists('project', $propertyData) ? $propertyData['project'] : NULL,
                'project_name'=> array_key_exists('projectName', $propertyData) ? $propertyData['projectName'] : NULL,
                'property_type'=> array_key_exists('propertyType', $propertyData) ? $propertyData['propertyType'] : NULL,
                'psf'=> array_key_exists('psf', $propertyData) ? $propertyData['psf'] : 0,
                'rental_yield'=> array_key_exists('rentalYield', $propertyData) ? $propertyData['rentalYield'] : 0,
                'retail_Type'=> array_key_exists('retailType', $propertyData) ? $propertyData['retailType'] : NULL,
                'rented_price'=> array_key_exists('rentedPrice', $propertyData) ? $propertyData['rentedPrice'] : 0,
                'roof_terrance'=> array_key_exists('roofTerrance', $propertyData) ? $propertyData['roofTerrance'] : NULL,
                'room_position'=> array_key_exists('roomPosition', $propertyData) ? $propertyData['roomPosition'] : NULL,
                'server_room'=> array_key_exists('serverRoom', $propertyData) ? $propertyData['serverRoom'] : NULL,
                'status'=> array_key_exists('status', $propertyData) ? $propertyData['status'] : NULL,
                'submit'=> array_key_exists('submit', $propertyData) ? $propertyData['submit'] : NULL,
                'top_year'=> array_key_exists('topYear', $propertyData) ? $propertyData['topYear'] : 0,
                'swimming_pool'=> array_key_exists('swimmingPool', $propertyData) ? $propertyData['swimmingPool'] : NULL,
                'tenanted_price'=> array_key_exists('tenantedPrice', $propertyData) ? $propertyData['tenantedPrice'] : 0,
                'tenure'=> array_key_exists('tenure', $propertyData) ? $propertyData['tenure'] : NULL,
                'town'=> array_key_exists('town', $propertyData) ? $propertyData['town'] : NULL,
                'unit_no'=> array_key_exists('unitNo', $propertyData) ? $propertyData['unitNo'] : NULL,
                'valuation'=> array_key_exists('valuation', $propertyData) ? $propertyData['valuation'] : 0,
                'vehicle_access'=> array_key_exists('vehicleAccess', $propertyData) ? $propertyData['vehicleAccess'] : NULL,
                'creator_id' => $creator? $creator->id: NULL
            ];
            Property::create($newPro);
        }
    }

}