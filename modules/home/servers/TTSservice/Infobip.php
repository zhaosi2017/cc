<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/9
 * Time: 上午11:31
 */


namespace app\modules\home\servers\TTSservice;
use app\modules\home\models\CallRecord;
use yii\db\Exception;


class Infobip extends TTSAbstarct {

    private $authorization = 'Basic Y2FsbHVvbmxpbmU6dHhjLC4vMTIz';
    private $uri = 'https://api.infobip.com/tts/3/advanced';






    public function sendMessage(){
         $send_data = '{
   "bulkId": "BULK-ID-123-xyz",
   "messages": [
      {
         "from": "1234567",
         "destinations": [
            {
               "to": "+85586564836",
               "messageId": "MESSAGE-ID-123-xyz"
            }
         ],
         "text": "Test Voice message.",
         "language": "en",
         "speechRate": 1,
         "notifyUrl": "https://test.callu.online/home/tts/test-sinch",
         "notifyContentType": "application/json",
         "callbackData":"DLR callback data",
         "validityPeriod": 720,
         "sendAt": "2016-07-07T17:00:00.000+01:00",
         "record": false,
         "repeatDtmf": "123#",
         "ringTimeout": 45,
         "dtmfTimeout": 10,
         "callTimeout": 130,
         "machineDetection": "DISABLE"         
      }
   ],
   "tracking":{
         "track": "VOICE",
         "type": "MY_CAMPAIGN"
   }
}';
echo "<pre>";
         print_r($send_data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $send_data,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: ".$this->authorization,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        print_r(json_decode($response , true));
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;
        }

    }

    public function event($data){

    }





}