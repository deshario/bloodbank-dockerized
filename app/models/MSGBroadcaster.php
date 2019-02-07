<?php

namespace app\models;

use DateTime;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Yii;

class MSGBroadcaster extends \yii\db\ActiveRecord
{
    public function sendSingleBroadcast($title,$message,$token){
        $apiKey = \Yii::$app->params['firebase_api'];
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $note = new Notification($title, $message);
        $note->setIcon('R.mipmap.ic_launcher')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = new Message();
        $message->addRecipient(new Device($token));
        $message->setNotification($note)->setData(array('someId' => 111));
        $response = $client->send($message);
        $status = $response->getStatusCode();
        return $status;
    }

    public function sendMultipleBroadcast($title,$message,$tokens){
        $apiKey = \Yii::$app->params['firebase_api'];
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        $note = new Notification($title, $message);
        $note->setIcon('R.mipmap.ic_launcher')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message = new Message();
        for ($i = 0; $i < count($tokens); $i++) {
            $message->addRecipient(new Device($tokens[$i]));
        }
        $message->setNotification($note)->setData(array('someId' => 111));
        $response = $client->send($message);
        $status = $response->getStatusCode();
        return $status;
    }
}
