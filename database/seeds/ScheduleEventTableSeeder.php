<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\ScheduleEvent;
use App\Property;
use App\User;
use App\FileAsset;

class ScheduleEventTableSeeder extends Seeder {

    public function run()
    {
        $filename = public_path()."/Data/ScheduleEvent.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $schedules = $dataArr['results'];

        foreach ($schedules as $scheduleData) {
            $requester = null;
            if (array_key_exists('requester', $scheduleData)) {
                $objectId = $scheduleData['requester']['objectId'];
                $requester = User::where('object_id', $objectId)->first();
            }



            $receiver_agent = null;
            if (array_key_exists('receiverAgent', $scheduleData)) {
                $objectId = $scheduleData['receiverAgent']['objectId'];
                $receiver_agent = User::where('object_id', $objectId)->first();
            }

            $property = null;
            if (array_key_exists('targetProp', $scheduleData) ) {
                $objectId = $scheduleData['targetProp']['objectId'];
                $property = Property::where('object_id', $objectId)->first();
            }

            $image = null;
            if (array_key_exists('picture', $scheduleData)) {
                $pictureData = $scheduleData['picture'];

                $newPicture = [
                    "type" => $pictureData["__type"],
                    "name" => $pictureData["name"],
                    "url" => $pictureData["url"],
                    "source" => "Parse.com"
                ];

                $image = FileAsset::create($newPicture);
            }


            $newScheduleData = [
                'object_id' => $scheduleData['objectId'],
                'agent_accept' =>array_key_exists('agentAccept', $scheduleData)? $scheduleData['agentAccept'] : 0,
                'requester_accept' => array_key_exists('requesterAccept', $scheduleData) ? $scheduleData['requesterAccept'] : 0,
                'date_time' => $scheduleData['dateTime']['iso'],
                'is_reschedule' => array_key_exists('isReschedule', $scheduleData) ? $scheduleData['isReschedule'] : 0,
                'meetup_revenue' => array_key_exists('meetupVenue', $scheduleData) ? $scheduleData['meetupVenue'] : NULL,
                'project_name'=>array_key_exists('projectName', $scheduleData) ? $scheduleData['projectName'] : NULL,
                'project_type' =>array_key_exists('projectType', $scheduleData) ? $scheduleData['projectType'] : NULL,
                'image_id' => $image? $image->id :NULL,
                'property_id' => $property ? $property->id : NULL,
                'requester_id' => $requester ? $requester->id : NULL,
                'receiver_agent_id' => $receiver_agent ? $receiver_agent->id : NULL,
                'is_deleted' => array_key_exists('isDeleted', $scheduleData) ? $scheduleData['isDeleted'] : false
            ];

            ScheduleEvent::create($newScheduleData);
        }
    }

}