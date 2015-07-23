<?php

return array(

    'realJamesGoh' => array(
        'environment' =>'production',
        'certificate' => public_path() . "/Push/sg.com.hvsolutions.realJamesGoh/Push.pem",
        'passPhrase'  => env('NOTIFICATION_KEY'),
        'service'     =>'apns'
    ),
    'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'yourAPIKey',
        'service'     =>'gcm'
    )

);