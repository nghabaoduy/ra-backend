<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Userl;
use App\Partner;
use App\User;

class PartnerTableSeeder extends Seeder {

    public function run()
    {
        $filename = public_path()."/Data/Partner.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $partners = $dataArr['results'];

        foreach ($partners as $partnerData) {
            $creator = null;
            if (array_key_exists('creator', $partnerData)) {
                $objectId = $partnerData['creator']['objectId'];
                $creator = User::where('object_id', $objectId)->first();
            }


            $newPartnerData =[
                'object_id' => $partnerData['objectId'],
                'address' => array_key_exists('address', $partnerData)? $partnerData['address'] : NULL,
                'bank' => array_key_exists('bank', $partnerData)? $partnerData['bank'] : NULL,
                'company' => array_key_exists('company', $partnerData)? $partnerData['company'] : NULL,
                'contact' => array_key_exists('contact', $partnerData)? $partnerData['contact'] : NULL,
                'email' => array_key_exists('email', $partnerData)? $partnerData['email'] : NULL,
                'name' => array_key_exists('name', $partnerData)? $partnerData['name'] : NULL,
                'type' => array_key_exists('type', $partnerData)? $partnerData['type'] : NULL,
                'creator_id' => $creator ? $creator->id : 0,
            ];

            Partner::create($newPartnerData);


        }
    }

}