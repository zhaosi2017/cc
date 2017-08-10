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

    private $send_data = [
        'bulkId'=>'',
        'messages'=>[
            ['from'=>'',
              'destinations'=>[
                  'to'=>'',
                  'messageId'=>''
              ],
             "text"=>" 你好啊",
             "language"=> "zh-cn",
             "speechRate"=> 1,
             "notifyUrl"=> "https://103.235.171.147/home/tts/sinch-event",
             "notifyContentType"=> "application/json",
             "callbackData"=>"DLR callback data",
             "validityPeriod"=> 720,
             "sendAt"=> "2016-07-07T17:00:00.000+01:00",
             "record"=>false,
             "repeatDtmf"=> "123#",
             "ringTimeout"=> 45,
             "dtmfTimeout"=> 10,
             "callTimeout"=> 130,
             "machineDetection"=> "DISABLE"

            ]
        ],
        'tracking'=>[
            "track"=> "VOICE",
            "type"=> "MY_CAMPAIGN"
        ]

    ];


    private $messageAnwser_arr = [
        '5404'=>'timeout',
        '5484'=>'timeout',
        '5000'=>'answered',
        '5487' =>'failed',
        '5003'=>'unanwsered',
        '5002'=>'busy'
    ];



    public function sendMessage(){
        $this->send_data['bulkId'] = $this->uuid_v4();
        $this->send_data['messages']['from'] = $this->from;
        $this->send_data['messages']['from']['destinations']['to'] = $this->to;
        $this->send_data['messages']['from']['destinations']['messageId'] = $this->uuid_v4();
        $this->send_data['messages']['text'] = $this->messageText;
        $this->send_data['messages']['language'] = strtolower($this->Language);

        $body = json_encode( $this->send_data , true);




        $header = ['Accept'=>'application/json' , 'Content-type'=>'application/json' ,'Authorization'=>$this->authorization];
        $client = new \GuzzleHttp\Client();
        $request  = new \GuzzleHttp\Psr7\Request('POST' , $this->uri , $header , $body);
        $response = $client->send($request,['timeout' => 30]);
        if($response->getStatusCode() !== 200){
            return false;
        }
        $data = json_decode($response->getBody()  , true);
        if(!empty($data) &&  $data['messages'][0]['status']['groupId'] == 1){
            $this->messageId = $data['bulkId'];
            return true;
        }
        return false;
    }

    public function event($event_data){
        $this->messageId = $event_data['bulkId'];
        $result = isset($event_data['error']['id'])?$event_data['error']['id']:0;

        switch ($result){
            case 5000:
                $this->messageStatus =  CallRecord::Record_Status_Success;
                $this->messageAnwser = $this->messageAnwser_arr[5000];

                break;
            case 5003:
                $this->messageStatus =  CallRecord::Record_Status_NoAnwser;
                $this->messageAnwser = $this->messageAnwser_arr[5003];
                break;
            case 5002:
                $this->messageStatus =  CallRecord::Record_Status_Busy;
                $this->messageAnwser = $this->messageAnwser_arr[5002];
                break;
            default:
                $this->messageStatus =  CallRecord::Record_Status_Fail;
                $this->messageAnwser = $this->messageAnwser_arr[5487];
                break;
        }
        return 'ok';
    }



    private function uuid_v4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

}