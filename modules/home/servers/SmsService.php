<?php
namespace app\modules\home\servers;
use Yii;
class SmsService
{
    public function sendSms($number,$msg)
    {
        $sid = Yii::$app->params['twilio_api_key']; // Your Account SID from www.twilio.com/console
        $token = Yii::$app->params['twilio_api_secret']; // Your Auth Token from www.twilio.com/console
        $client = new \Twilio\Rest\Client($sid, $token);
        $message = $client->messages->create(
            $number, // Text this number
            array(
                'from' => '+19472224852', // From a valid Twilio number
                'body' => $msg
            )
        );
        if($message->status == 'queued')
        {
            return true;
        }
        return false;
    }
}
