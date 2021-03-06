<?php namespace App\Services;
/**
 * Created by PhpStorm.
 * User: duy work
 * Date: 5/2/2015
 * Time: 2:37 PM
 */

 class NotificationService {
     private $appIdentifier = null;
     private $appPassPhrase = null;
     private $deviceToken = null;
     private $appCert = 'Push.pem';
     private $appCertDev = 'PushDev.pem';
     private $localCert = null;
     private $isDev = 0;
     private $dev = false;
     public static $fp = null;

     public function __construct($appIdentifier, $deviceToken, $isDev = false)
     {
         if ($isDev) {
             $this->localCert = public_path() . "/Push/" . $appIdentifier . '/' . $this->appCertDev;
         }
         else {
             $this->localCert = public_path() . "/Push/" . $appIdentifier . '/' . $this->appCert;
         }
         $this->dev = true;
         $this->appIdentifier = $appIdentifier;
         $this->deviceToken = $deviceToken;
         $this->appPassPhrase = env('NOTIFICATION_KEY');
         $this->isDev = $isDev;

//dd($this->$isDev);
         //dd($this->localCert);
     }

     public function openSocket() {
         $ctx = stream_context_create();
         stream_context_set_option($ctx, 'ssl', 'local_cert',$this->localCert);
         stream_context_set_option($ctx, 'ssl', 'passphrase', $this->appPassPhrase);
         if ($this->isDev) {
             static::$fp = stream_socket_client(
                 'ssl://gateway.sandbox.push.apple.com:2195', $err,
                 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

         } else {

             static::$fp = stream_socket_client(
                 'ssl://gateway.push.apple.com:2195', $err,
                 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
         }

         if (!static::$fp) {
             return false;
             //return ['message' => "Failed to connect: $err $errstr" . PHP_EOL];
         }

     }

     public static function closeSocket() {
         if (static::$fp) {
             fclose(static::$fp);
             static::$fp = null;
         }
     }

     public function sendPush(array $data) {

         if (static::$fp == null) {
             $this->openSocket();
         }



         $data['sound'] = 'default';
         $body['aps'] = $data;


         $payload = json_encode($body);
         $msg = chr(0) . pack('n', 32) . pack('H*', $this->deviceToken) . pack('n', strlen($payload)) . $payload;

         $result = fwrite(static::$fp, $msg, strlen($msg));
         if ($result) {
             $message =  true;
         } else {
             $message =  false;
         }


         return $message;
     }
 }