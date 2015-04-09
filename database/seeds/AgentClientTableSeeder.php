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
use App\AgentClient;

class AgentClientTableSeeder extends Seeder  {

    public function run() {
        $filename = public_path()."/Data/AgentClientRecord.json";
        $contents = File::get($filename);

        $dataArr = json_decode($contents, true);

        $clients = $dataArr['results'];

        foreach ($clients as $clientData) {

            $agent = null;
            if (array_key_exists('agent', $clientData) ) {
                $objectId = $clientData['agent']['objectId'];
                $agent = User::where('object_id', $objectId)->first();
            }


            $client = null;
            if (array_key_exists('client', $clientData) ) {
                $objectId = $clientData['client']['objectId'];
                $client = User::where('object_id', $objectId)->first();
            }

            if ($client && $agent) {
                $newClientData = [
                    'object_id' => array_key_exists('objectId', $clientData) ? $clientData['objectId']: NULL,
                    'agent_id' => $agent->id,
                    'client_id' => $client->id
                ];

                AgentClient::create($newClientData);
            }
        }



    }
}