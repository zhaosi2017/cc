<?php

namespace app\modules\home\servers\TTSservice;
use app\modules\home\models\CallRecord;
use app\modules\home\models\Telegram;
use app\modules\home\models\User;
use app\modules\home\models\UserGentContact;
use app\modules\home\models\UserPhone;
use Yii;

/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/14
 * Time: 上午10:18
 */




class TTSservice{

    private static $_instance;
    /**
     * @var TTSAbstarct
     */
    private $third;

    public $loop;                   //重试次数
    public $from_user_id;           //呼叫的userid
    public $to_user_id;             //被呼叫的userid
    public $app_account_key;        //app类型
    public $call_num;               //除开本次呼叫，呼叫队列还剩余的呼叫次数
    public $call_type;              //本次呼叫的类型  联系电话  紧急联系人


    private function __construct()
    {
    }

    public static function init($className){

        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        if(class_exists($className)&& get_parent_class($className) == TTSAbstarct::class){
            self::$_instance->third = new $className();
        }else{
            self::$_instance= null;
        }
         return self::$_instance;
    }


    public function __get($key)
    {
       return  self::$_instance->third;
    }

    public function __set($key, $value)
    {
        self::$_instance->third->$key = $value;
    }

    /**
     * 发送消息 建立发送消息队列
     * 只需要发送一次 剩下的交给回调来处理
     */
    public function sendMessage($call_type){

        $from_user = User::findOne($this->from_user_id)->toArray();                   //主叫人
        $to_user   = User::findOne($this->to_user_id)->toArray();                     //被叫人
        $sends     = $this->_getCallNumbers($call_type);
        $this->third->to = $sends[0]['to'];
        $this->call_type = $sends[0]['call_type'];
        $result = $this->third->sendMessage();
        if(!$result){               //发生异常直接返回
            return false;
        }
        unset($sends[0]);

/************这里处理两个redis请求 1。存入本次返回的uuid和用户信息* 2 。存入所有用户的备用号码**************/
        $list_key = get_class($this->third).'_send_'.$this->third->messageId;
        foreach($sends as $send){
            $send['text'] = !empty($this->third->messageText_more)?$this->third->messageText_more:$this->third->messageText;
            $tmp = json_encode($send);
            Yii::$app->redis->lpush($list_key , $tmp);
            $this->call_num++;
        }
        $this->_saveCallBackToRedis($list_key , $from_user , $to_user);
        return true;
    }

    /**
     * @param $call_type
     * @return array
     * 根据呼叫类型 取得呼叫的联系电话集
     */
    private function _getCallNumbers($call_type){
        $sends = [];
        $send_data = [
            'text'=>$this->third->messageText,
            'from'=> $this->third->from,
            'message_type'=>$this->third->messageType,
            'from_user_id'=>$this->from_user_id,
            'to_user_id' =>$this->to_user_id,
        ];
        if($call_type == CallRecord::Record_Type_none){                                   //正常呼叫
            $to_phones = UserPhone::findAll(array('user_id'=>$this->to_user_id));         //被呼叫者的电话集合
            foreach($to_phones as $phone){
                $send_data['to'] = '+'.$phone->phone_country_code.$phone->user_phone_number;
                $send_data['call_type'] = CallRecord::Record_Type_none;
                $sends[] = $send_data;
            }
        }elseif($call_type == CallRecord::Record_Type_emergency){                         //紧急联系人呼叫
            $to_phones = UserGentContact::findAll(array('user_id'=>$this->to_user_id));   //被呼叫者的紧急联系人集合
            foreach($to_phones as $phone){
                $send_data['to'] = '+'.$phone->contact_country_code.$phone->contact_phone_number;
                $send_data['call_type'] = CallRecord::Record_Type_emergency ;
                $sends[] = $send_data;
            }

        }
        return $sends;
    }

    /**
     * @param $list_key
     * @param $from_user User
     * @param $to_user   User
     *
     */
    private function _saveCallBackToRedis($list_key,$from_user , $to_user){
       echo $call_key = get_class($this->third).'_callid_'.$this->third->messageId;

        Yii::$app->redis->hset($call_key, 'time', time());
        Yii::$app->redis->hset($call_key, 'from_id', $this->from_user_id);                      //发起者的用户id
        Yii::$app->redis->hset($call_key , 'to_id' , $this->to_user_id);                        //被叫者的id
        Yii::$app->redis->hset($call_key , 'from_account' ,$from_user[$this->app_account_key]); //主叫的app账号
        Yii::$app->redis->hset($call_key , 'to_account' ,  $to_user[$this->app_account_key]);   //被叫的app账号
        Yii::$app->redis->hset($call_key , 'from_nickname' ,$from_user['nickname']);            //主叫的app账号
        Yii::$app->redis->hset($call_key , 'to_nickname' , $to_user['nickname']);               //被叫的app账号
        Yii::$app->redis->hset($call_key , 'from_number' , $from_user['phone_number']);         //主叫号码
        Yii::$app->redis->hset($call_key , 'to_number' , $this->third->to);                     //被叫号码
        Yii::$app->redis->hset($call_key , 'type' ,       $this->call_type);             //呼叫的类型
        Yii::$app->redis->hset($call_key , 'app_acount_key' ,$this->app_account_key);           //呼叫发起的app账号昵称  字段名

        Yii::$app->redis->hset($call_key , 'list_key' , $list_key);                             //记录队列的key
        Yii::$app->redis->expire($call_key, 30*60);
    }


    /**
     * @param $call_array
     * @return bool
     * 用队列发起更多的电话请求
     * 进入到这里表示上一次呼叫已经失败
     * 所以提示一下用户
     */
    private function _moreSendMessage($call_array){

        $list_key               = $call_array['list_key'];
        $this->call_type        = $call_array['type'];
        $this->app_account_key  = $call_array['app_acount_key'];
        $this->sendMessageToRobot($this->app_account_key , $call_array['from_id'] , '呼叫失败'  );
        if( $this->call_num = Yii::$app->redis->llen($list_key) <= 0 ){         //队列空
            if($call_array['type'] == CallRecord::Record_Type_emergency){       //紧急联系人呼叫完

            }else{                                                              //联系电话呼叫完成

            }
            return true;
        }
        $send = Yii::$app->redis->lpop($list_key);
        $send = json_decode($send ,true);

        $this->third->to            = $send['to'];
        $this->third->messageText   = $send['text'];
        $this->third->from          = $send['from'];
        $this->third->messageType   = $send['message_type'];

        $this->call_type            = $send['call_type'];
        $result = $this->third->sendMessage();                                  //发送一个新的消息

        if(!$result){                                                           //发生异常时删除redis的相关数据
            Yii::$app->redis->del($list_key);
            return false;
        }
        $cacheKey = get_class($this->third).'_callid_'.$this->third->messageId;

        foreach($call_array as $key=>$item){
            if($key == 'to_number' ){
                $item = $this->third->to ;
            }
            Yii::$app->redis->hset($cacheKey , $key ,$item);
        }
        Yii::$app->redis->expire($cacheKey, 30*60);
        return true;

    }

    private function redisGetVByK($cacheKey){

        $cache_keys = Yii::$app->redis->hkeys($cacheKey);
        $catch_vals = Yii::$app->redis->hvals($cacheKey);
        return array_combine($cache_keys , $catch_vals);

    }

    /**
     *消息的回调处理
     */
    public function event($event_data){

        $result =  $this->third->event($event_data);
        $cacheKey = get_class($this->third).'_callid_'.$this->third->messageId;
        $catch_call = $this->redisGetVByK($cacheKey);
        if($this->third->messageStatus == CallRecord::Record_Status_Success){   //呼叫成功 回复一条消息 终止任务
            $list_key = $catch_call['list_key'];
            Yii::$app->redis->del($list_key);
        }else{                                                                  //呼叫失败 继续
            $this->_moreSendMessage($catch_call);
        }
        $this->_saveRecord($catch_call);
        Yii::$app->redis->del($cacheKey);

        return $result;   //回应数据 跟业务无关
    }

    /**
     * @param array $data
     * 保存通话记录
     */
    private function _saveRecord(Array $data){

        $callRecord = new CallRecord();
        $callRecord->active_call_uid = $data['from_id'];
        $callRecord->unactive_call_uid = $data['to_id'];
        $callRecord->active_account = $data['from_account'];      //主叫的app账号
        $callRecord->unactive_account = $data['to_account'];      //被叫的app账号
        $callRecord->active_nickname = $data['from_nickname'];    //主叫昵称
        $callRecord->unactive_nickname = $data['to_nickname'];    //被叫昵称
        $callRecord->contact_number = $data['from_number'];       //主叫号码
        $callRecord->unactive_contact_number = $data['to_number'];//被叫号码

        $callRecord->status = $this->third->messageStatus;        //呼叫状态
        $callRecord->call_time = $data['time'];                   //呼叫发起时间
        $callRecord->type = $data['type'];                        //呼叫的类型 紧急联系人呼叫 ？ 正常呼叫？
        $callRecord->save();
    }

    /**
     * @param $appName
     * @param $appUid
     * @param $text
     *
     */
    private function sendMessageToRobot($appName, $uid, $text, $keyBoard = '')
    {
        $user = User::findOne($uid);
        switch ($appName) {
            case 'telegram_name':
                $uri = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
                if (empty($keyBoard)) {
                    $sendData = [
                        'chat_id' => $user->telegram_user_id,
                        'text' => $text,
                    ];
                } else {
                    $sendData = [
                        'chat_id' => $user->telegram_user_id,
                        'text' => $text,
                        'reply_markup' => [
                            'inline_keyboard' => $keyBoard,
                        ],
                    ];
                }
                break;
            case 'potato_name':
                $uri ='http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
                if (empty($keyBoard)) {
                    $sendData = [
                        'chat_type' => 1,
                        'chat_id' => $user->potato_user_id,
                        'text' => $text,
                    ];
                } else {
                    $sendData = [
                        'chat_type' => 1,
                        'chat_id' => $user->potato_user_id,
                        'text' => $text,
                        'inline_markup' => $keyBoard,
                    ];
                }
                break;
            default :
                break;
        }
        $this->sendRequest($uri, $sendData);
    }

    private function sendRequest($url = null, $sendData)
    {
        if (is_array($this->sendData)) {
            $sendData =  json_encode($this->sendData,JSON_UNESCAPED_UNICODE) ;
        }
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $sendData,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                ),
            )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if (empty($url)) {
            $response = json_decode($response, true);
            if (!$response['ok']) {
                return "error_cod #:".$response['error_code'].', description: '.$response['description'];
            }
        }

        if ($err) {
            return "error #:" . $err;
        } else {
            return $response;
        }

    }


}