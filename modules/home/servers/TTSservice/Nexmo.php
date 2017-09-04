<?php
namespace app\modules\home\servers\TTSservice;
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/14
 * Time: 上午10:19
 */
use app\modules\home\models\CallRecord;
use \Nexmo\Client\Credentials\Basic;
use \Nexmo\Client\Credentials\Keypair;
use \Nexmo\Client;
use \Nexmo\Client\Credentials\Container;
use Yii;
use yii\db\Exception;

class Nexmo extends  TTSAbstarct{

    private $_answerUrl = '/home/tts/nexmo-anwser';
    private $_eventUrl = '/home/tts/nexmo-event';

    private $apiKey = '85704df7';
    private $apiScret = '755026fdd40f34c2';
   // private $applicationId = '570db7b5-09cb-45b3-a097-e0b8e0bcec65';
    private $applicationId = '454eb4c4-1fdd-4b4b-9423-937c80f01bb8';
    /**
     * @var array
     * 记录呼叫的状态
     */
    private $messageAnwser_arr = [
            'timeout'=>'timeout',
            'answered'=>'answered',
            'failed' =>'failed',
            'unanwsered'=>'unanwsered',
            'busy'=>'busy',
            'rejected'=>'busy'
    ];

    public function __construct()
    {
        $base_uri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
        //$base_uri = 'https://test.callu.online';
        $this->_eventUrl = $base_uri.$this->_eventUrl;
        $this->_answerUrl = $base_uri.$this->_answerUrl;
        $this->voice = 'Joey';
    }

    public function sendMessage()
  {
      if($this->messageType == 'SMS'){
          return $this->sendSMS();
      }elseif($this->messageType == 'TTS'){
          return $this->sendTTS();
      }
      return false;


  }


  public function sendTTS(){

      $basic = new Basic($this->apiKey, $this->apiScret);
      $privatePath = Yii::getAlias('@app').'/config/'.'private.key';
      $keypair = new Keypair(file_get_contents($privatePath), $this->applicationId);
      $client = new Client(new Container($basic, $keypair));
      $this->setAnwser();
      try{
          $call = $client->calls()->create([
              'to' => [[
                  'type' => 'phone',
                  'number' => $this->to
              ]],
              'from' => [
                  'type' => 'phone',
                  'number' => '12345678'
              ],
              'answer_url' => [
                  $this->_answerUrl,
              ],
              'event_url' => [
                  $this->_eventUrl,
              ]
          ]);
      }catch (Exception $e){
          $call = jsone_encode(['error'=>$e->getMessage()]);
      }
      $call = json_encode($call, JSON_UNESCAPED_UNICODE);
      $call = json_decode($call, true);
      if(isset($call['uuid']) && !empty($call['uuid'])){
          $this->messageId = $call['uuid'];
          return true;
      }
      return false;
  }

  public function sendSMS(){


  }

  public function event($event_data)
  {
      $this->messageId = $event_data['uuid'];
      if($event_data['status'] == 'busy' || $event_data['status'] == 'rejected'){
          $this->messageAnwser = $this->messageAnwser_arr['busy'];
          $this->messageStatus = $event_data['result'] = CallRecord::Record_Status_Fail;
      }elseif($event_data['status'] == 'answered'){
          $this->messageAnwser = $this->messageAnwser_arr['answered'];
          $this->messageStatus = $event_data['result'] = CallRecord::Record_Status_Success;
      }elseif($event_data['status'] == 'failed'){
          $this->messageAnwser = $this->messageAnwser_arr['failed'];
          $this->messageStatus = $event_data['result'] = CallRecord::Record_Status_Fail;
      }elseif($event_data['status'] == 'unanwsered'){
          $this->messageAnwser = $this->messageAnwser_arr['unanwsered'];
          $this->messageStatus = $event_data['result'] = CallRecord::Record_Status_Fail;
      }elseif($event_data['status'] == 'timeout'){
          $this->messageAnwser = $this->messageAnwser_arr['timeout'];
          $this->messageStatus = $event_data['result'] = CallRecord::Record_Status_Fail;
      }else{
          $this->discardEvent();   //其他的回调直接丢弃不处理
      }
      return true;
  }

    /**
     * 存储电话内容等待nexmo来领取
     */
  private function setAnwser(){

      $cacheKey = $this->from.time();
      $tmp = [
          'action' => 'talk',
          'loop' => $this->loop,
          'lg' => $this->Language,
          'voiceName' => $this->voice,
          'text' => $this->messageText,
      ];

      $conference = [
          $tmp,
      ];
      $conferenceCacheKey = $cacheKey.'_pre';
      Yii::$app->redis->set($conferenceCacheKey, json_encode($conference, JSON_UNESCAPED_UNICODE));
      Yii::$app->redis->expire($conferenceCacheKey, 5*60);

  }




}