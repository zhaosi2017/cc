<?php
namespace app\modules\home\servers\TTSservice;
use app\modules\home\models\CallRecord;
use yii\db\Exception;

/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/14
 * Time: 上午10:29
 */
class Sinch extends TTSAbstarct {
//正式
    private $auth_id = '0221f92e-7fbf-4df2-9eb1-c4a965b14fc4';
    private $auth_key = 'D64MIM3RJ0ijv1r5K7fcsQ==';
//测试
//    private $auth_id = '8d79c1a9-ab0c-4319-a5d4-ae01a2d2f80d';
//    private $auth_key = 'k97ZeapOKEC7+l+TyoemDw==';


    private $body;          //提交数据
    private $authorization; //数据验证
    private $timestamp ;    //提交时间

    private $uri = 'https://callingapi.sinch.com/v1/callouts';

    private $messageAnwser_arr = [
        'timeout'=>'timeout',
        'ANSWERED'=>'answered',
        'FAILED' =>'failed',
        'NOANSWER'=>'unanwsered',
        'BUSY'=>'busy'
    ];




    public function sendMessage()
    {
       if($this->messageType == 'SMS'){
           return $this->sendSMS();
       }elseif($this->messageType == 'TTS'){
           return $this->sendTTS();
       }
       return false;
    }

    public function event($event_data)
    {
        $event = $event_data['event'];
        switch($event){
            case 'ice':
                $svaml = $this->Event_ICE($event_data);
                break;
            case 'ace':
                $svaml = $this->Event_ACE($event_data);
                break;
            case 'dice':
                $svaml = $this->Event_DICE($event_data);
                break;
            default:
                $svaml = 'OK';
                break;
        }

        return $svaml;
    }

    /**
     * @return string
     * 呼叫事件
     */
    private function Event_ICE($event_data){
            return '';

    }

    /**
     * @return string
     * 
     *呼叫应答
     */
    private function Event_ACE($event_data){

        return '';
    }

    /**
     * @return string
     * 通话结束
     */
    private function Event_DICE($event_data){

        $this->messageId = $event_data['callid'];     //通话id
        //$this->messageStatus = $event_data['result'] == 'ANSWERED' ?CallRecord::Record_Status_Success:CallRecord::Record_Status_Fail; //通话 结果
        switch ($event_data['result']){
            case 'ANSWERED':
                $this->messageStatus =  CallRecord::Record_Status_Success;
                break;
            case 'FAILED':
                $this->messageStatus =  CallRecord::Record_Status_Fail;
                break;
            case 'NOANSWER':
                $this->messageStatus =  CallRecord::Record_Status_NoAnwser;
                break;
            case 'BUSY':
                $this->messageStatus =  CallRecord::Record_Status_Busy;
                break;
            default:
                $this->messageStatus =  CallRecord::Record_Status_Fail;
                break;
        }
        $this->duration = isset($event_data['duration'])?$event_data['duration']:0;    //通话时间
        $this->messageAnwser = $this->messageAnwser_arr[$event_data['result']];
        return 'OK';
    }
    /**
     *短信
     *
     */
    public function sendSMS(){

    }

    public function getNumbers(){

        $this->timestamp = date("c");
        $path                  = "/v1/configuration/numbers/";
        $content_type          = "application/json";
        $canonicalized_headers = "x-timestamp:" . $this->timestamp;

        $content_md5 = base64_encode( md5( utf8_encode('{}'), true ));
        $string_to_sign =
            "GET\n".
            $content_md5."\n".
            $content_type."\n".
            $canonicalized_headers."\n".
            $path;
        $signature = base64_encode(hash_hmac("sha256", utf8_encode($string_to_sign), base64_decode($this->auth_key), true));
        $this->authorization = "Application " . $this->auth_id . ":" . $signature;

        $curl = curl_init('https://callingapi.sinch.com/v1/configuration/numbers/');
        curl_setopt($curl, CURLOPT_HTTPHEADER, [ 'content-type: '."application/json",
                                                        'x-timestamp:' . $this->timestamp,
                                                        'authorization:' . $this->authorization]
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl , CURLOPT_TIMEOUT, 20);
        try{
            $curl_response = curl_exec($curl);

        }catch (Exception $e){
            $this->error('Curl error: '. curl_error($curl));
        }
        curl_close($curl);

        return $curl_response;
    }

    /**
     * 语音电话
     */
    public function sendTTS(){
        if(strpos($this->to , '+') !== false){
            $this->to = '+'.trim($this->to ,'+');
        }
        $text = '';
        for($i=1; $i <= $this->loop ; $i++){
            $text .=' '.$this->messageText;
        }
        $this->body = json_encode(
            ['method'=>'ttsCallout',
                "ttsCallout"=>[
                    "cli" => "+493022409402",
                    //"cli" => "+62 279451",
                    "destination" =>[ "type" => "number", "endpoint" =>$this->to ],
                    "domain" => "pstn",
                    "custom" =>"customData",
                    "locale" => $this->Language,
                    "prompts" =>'#tts['.$text.'];myprerecordedfile',
                    'enabledice' => true,
                ],
            ]);
            $this->signature();
            $response = $this->_curl();
            $response  =json_decode($response);
            if(!empty($response) && isset($response->callId) && !empty($response->callId)){
                $this->messageId = $response->callId;
            }else{
                 return false;
            }
                 return true;
    }

    /**
     * 数据签名
     */
    private function signature(){
        $this->timestamp = date("c");
        $path                  = "/v1/callouts";
        $content_type          = "application/json";
        $canonicalized_headers = "x-timestamp:" . $this->timestamp;

        $content_md5 = base64_encode( md5( utf8_encode($this->body), true ));
        $string_to_sign =
            "POST\n".
            $content_md5."\n".
            $content_type."\n".
            $canonicalized_headers."\n".
            $path;
        $signature = base64_encode(hash_hmac("sha256", utf8_encode($string_to_sign), base64_decode($this->auth_key), true));
        $this->authorization = "Application " . $this->auth_id . ":" . $signature;
    }

    /**
     * 发送一个消息
     */
    private function _curl(){

        $curl = curl_init($this->uri);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [ 'content-type: '."application/json",
                                                        'x-timestamp:' . $this->timestamp,
                                                        'authorization:' . $this->authorization]
                    );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl , CURLOPT_TIMEOUT, 20);
        try{
            $curl_response = curl_exec($curl);

        }catch (Exception $e){
            $this->error('Curl error: '. curl_error($curl));
        }
        curl_close($curl);


        $fileName = 'sinch_'.date('Y-m-d', time()).'.txt';
        file_put_contents('/tmp/'.$fileName, var_export($this->body, true).PHP_EOL, 8);
        file_put_contents('/tmp/'.$fileName, var_export($curl_response, true).PHP_EOL, 8);
        return $curl_response;
    }

}

