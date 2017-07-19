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

    private $auth_id = '893b8449-294a-4ee7-8f5f-0248d76588b7';
    private $auth_key = 'oV94O5CvBUClPO9x1EIg3Q==';

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
        file_put_contents('/tmp/test_telegram.log' , var_export($this->messageType.PHP_EOL,true));
       if($this->messageType == 'SMS'){
           return $this->sendSMS();
       }elseif($this->messageType == 'TTS'){
           file_put_contents('/tmp/test_telegram.log' , var_export('TTS',true));
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
        $this->messageStatus = $event_data['result'] == 'ANSWERED' ?CallRecord::Record_Status_Success:CallRecord::Record_Status_Fail; //通话 结果
        $this->duration = $event_data['duration'];    //通话时间
        return 'OK';
    }

    /**
     *短信
     *
     */
    public function sendSMS(){

    }


    /**
     * 语音电话
     */
    public function sendTTS(){
        $this->body = json_encode(
            ['method'=>'ttsCallout',
                "ttsCallout"=>[
                    "cli" => "46000000000",
                    "destination" =>[ "type" => "number", "endpoint" =>$this->to ],
                    "domain" => "pstn",
                    "custom" =>"customData",
                    "locale" => $this->Language,
                    "prompts" =>'#tts['.$this->messageText.'];myprerecordedfile',
                    'enabledice' => true,
                ],
            ]);
        file_put_contents('/tmp/test_telegram.log' , var_export($this->body,true));
            $this->signature();
            $response = $this->_curl();
            $response  =json_decode($response);
            file_put_contents('/tmp/test_telegram.log' , var_export($response , true) ,8);
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

        return $curl_response;
    }

}

