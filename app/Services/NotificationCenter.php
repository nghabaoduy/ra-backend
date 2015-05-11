<?php namespace App\Services;
/**
 * Created by PhpStorm.
 * User: duy work
 * Date: 5/11/2015
 * Time: 9:35 PM
 */


class NotificationCenter {
    private $appIdentifier = null;
    private $appPassPhrase = null;
    private $appCert = 'Push.pem';
    private $appCertDev = 'PushDev.pem';
    private $localCert = null;
    private $isDev = 0;
    private $dev = false;
    private $fp;

    public function __construct($appIdentifier, $deviceToken, $isDev = false)
    {
        if ($isDev) {
            $this->localCert = public_path() . "/Push/" . $appIdentifier . '/' . $this->appCertDev;
        }
        else {
            $this->localCert = public_path() . "/Push/" . $appIdentifier . '/' . $this->$appCert;
        }
        $this->dev = true;
        $this->appIdentifier = $appIdentifier;
        $this->appPassPhrase = env('NOTIFICATION_KEY');
        $this->isDev = $isDev;




    }

    public function openSocket() {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert',$this->localCert);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->appPassPhrase);
        if ($this->isDev) {
            $this->fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        } else {
            $this->fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        }

        if (!$this->fp) {
            return;
            //return ['message' => "Failed to connect: $err $errstr" . PHP_EOL];
        }
    }

    function __destruct() {
        fclose($this->fp);
    }

    public function sendPush(array $data, $device_token) {
        $data['sound'] = 'default';
        $body['aps'] = $data;
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($this->fp, $msg, strlen($msg));
        if ($result) {
            return true;
        } false;
    }
}