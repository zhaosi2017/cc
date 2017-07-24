<?php

namespace app\modules\home\servers\TTSservice;
use app\modules\home\models\CallRecord;
use app\modules\home\models\Potato;
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
    public $app_type;               //app类型
    public $call_num;               //除开本次呼叫，呼叫队列还剩余的呼叫次数
    public $call_type;              //本次呼叫的类型  联系电话  紧急联系人
    public $app_obj;                //调用app对象


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
    public function sendMessage($call_type , $app_obj){

        $from_user = User::findOne($this->from_user_id)->toArray();                   //主叫人
        $to_user   = User::findOne($this->to_user_id)->toArray();                     //被叫人
        $sends     = $this->_getCallNumbers($call_type, $to_user);
        $send_ = array_shift($sends);
        $this->third->to = $send_['to'];
        $this->call_type = $send_['call_type'];
        $this->app_obj = $app_obj;
        $this->app_obj->call_set_name();
        $this->app_obj->startCall($this->call_type ,['to_account'=>$this->app_obj->first_contact_name,'nickname'=>$send_['nickname']] );
        if(!$this->third->sendMessage()){                               //发生异常直接返回 提示呼叫失败
            $this->app_obj->exceptionCall();
            return false;
        }

        $list_key = get_class($this->third).'_send_'.$this->third->messageId;
        foreach($sends as $send){
            $send['text'] = $this->third->messageText;
            $tmp = json_encode($send);
            Yii::$app->redis->lpush($list_key , $tmp);
            $this->call_num++;
        }
        $this->_saveCallBackToRedis($list_key , $from_user , $to_user ,$send_);
        return true;
    }

    /**
     * @param $call_type
     * @return array
     * 根据呼叫类型 取得呼叫的联系电话集
     */
    private function _getCallNumbers($call_type , $to_user){
        $sends = [];
        $send_data = [
            'text'=>$this->third->messageText,
            'from'=> $this->third->from,
            'message_type'=>$this->third->messageType,
            'from_user_id'=>$this->from_user_id,
            'to_user_id' =>$this->to_user_id,
        ];
        if($call_type == CallRecord::Record_Type_none){                                   //正常呼叫
            $to_phones = UserPhone::find()->where(array('user_id'=>$this->to_user_id))->orderBy('id')->all();         //被呼叫者的电话集合
            foreach($to_phones as $phone){
                $send_data['to'] = '+'.$phone->phone_country_code.$phone->user_phone_number;
                $send_data['call_type'] = CallRecord::Record_Type_none;
                $send_data['nickname']  = $to_user['nickname'];
                $sends[] = $send_data;
            }
        }elseif($call_type == CallRecord::Record_Type_emergency){                         //紧急联系人呼叫
            $to_phones = UserGentContact::find()->where(array('user_id'=>$this->to_user_id))->orderBy(' id ')->all();   //被呼叫者的紧急联系人集合
            foreach($to_phones as $phone){
                $send_data['to'] = '+'.$phone->contact_country_code.$phone->contact_phone_number;
                $send_data['call_type'] = CallRecord::Record_Type_emergency ;
                $send_data['nickname']  = $phone['contact_nickname'];
                $sends[] = $send_data;
            }
        }
        return $sends;
    }

    /**
     * @param $list_key
     * @param $from_user User
     * @param $to_user   User
     */
    private function _saveCallBackToRedis($list_key,$from_user , $to_user , $send){
        $call_key = get_class($this->third).'_callid_'.$this->third->messageId;
        if($this->app_type == 'telegram'){
            $from_app_account_name = $from_user['telegram_name'];
            $from_app_account_id   = $from_user['telegram_user_id'];
            $to_app_account_id     = $to_user['telegram_user_id'];
            $to_app_account_name   = $to_user['telegram_name'];
        }elseif($this->app_type == 'potato'){
            $from_app_account_name = $from_user['potato_name'];
            $from_app_account_id   = $from_user['potato_user_id'];
            $to_app_account_id     = $from_user['potato_user_id'];
            $to_app_account_name   = $to_user['potato_name'];
        }else{
            $from_app_account_name = '';
            $to_app_account_name ='';
            $from_app_account_id   = 0;
            $to_app_account_id     = 0;
        }

        Yii::$app->redis->hset($call_key , 'time', time());
        Yii::$app->redis->hset($call_key , 'from_id', $this->from_user_id);                     //发起者的用户id
        Yii::$app->redis->hset($call_key , 'to_id' , $this->to_user_id);                        //被叫者的id
        Yii::$app->redis->hset($call_key , 'from_account' ,$from_app_account_name);             //主叫的app账号名
        Yii::$app->redis->hset($call_key , 'to_account' ,  $to_app_account_name);               //被叫的app账号名
        Yii::$app->redis->hset($call_key , 'from_nickname' ,$from_user['nickname']);            //主叫的昵称
        Yii::$app->redis->hset($call_key , 'to_nickname' , $to_user['nickname']);               //被叫的昵称
        Yii::$app->redis->hset($call_key , 'from_number' , $from_user['phone_number']);         //主叫号码
        Yii::$app->redis->hset($call_key , 'to_number' , $this->third->to);                     //被叫号码
        Yii::$app->redis->hset($call_key , 'call_type' ,       $this->call_type);               //呼叫号码的类型  联系号码 紧急联系人号码
        Yii::$app->redis->hset($call_key , 'app_type' ,$this->app_type);                        //呼叫发起的app类型
        Yii::$app->redis->hset($call_key , 'nickname' ,$send['nickname']);                      //被叫的昵称
        Yii::$app->redis->hset($call_key , 'language' ,$to_user['language']);                   //呼叫的语言
        Yii::$app->redis->hset($call_key , 'app_from_account_id' , $from_app_account_id);       //主叫的app id
        Yii::$app->redis->hset($call_key , 'app_to_account_id' , $to_app_account_id);           //被叫的app id
        Yii::$app->redis->hset($call_key , 'app_to_account_first' , $this->app_obj->first_contact_name);//主叫叫的app name
        Yii::$app->redis->hset($call_key , 'app_to_account_last' , $this->app_obj->last_contact_name);  //被叫的app name


        Yii::$app->redis->hset($call_key , 'list_key' , $list_key);                             //记录队列的key
        Yii::$app->redis->expire($call_key, 30*60);
    }




    /**
     *消息的回调处理
     */
    public function event($event_data){

        $result =  $this->third->event($event_data);
        $cacheKey = get_class($this->third).'_callid_'.$this->third->messageId;
        $catch_call = $this->_redisGetVByK($cacheKey);
        if(empty($catch_call)){
            return;
        }
        $this->_Create_app($catch_call);
        if($this->third->messageStatus == CallRecord::Record_Status_Success){   //呼叫成功 回复一条消息 终止任务

            $this->app_obj->sendCallSuccess($catch_call['to_account']);

            $list_key = $catch_call['list_key'];
            Yii::$app->redis->del($list_key);
        }else{                                                                  //呼叫失败 继续
            $tmp_call_name = (CallRecord::Record_Type_emergency == $catch_call['call_type'])?$catch_call['nickname']: $this->app_obj->first_contact_name;
            $this->app_obj->sendCallFailed( $tmp_call_name , $this->third->messageAnwser);
            $this->_moreSendMessage($catch_call);
        }
        $this->_saveRecord($catch_call);

        return $result;   //回应数据 跟业务无关
    }

    /**
     * @param array $data
     * @return bool
     * 创建app发送对象
     */
    private function  _Create_app(Array $data){

        if($data['app_type'] == 'telegram'){
            $this->app_obj = new Telegram();
            $this->app_obj->telegramUid =  $data['app_from_account_id'];
        }elseif($data['app_type'] == 'potato'){
            $this->app_obj = new Potato();
            $this->app_obj->potatoUid   =  $data['app_from_account_id'];
        }else{
            return false;
        }
        $this->app_obj->language    =  $data['language'];
        $this->app_obj->first_contact_name = $data['app_to_account_first'];
        $this->app_obj->last_contact_name  =  $data['app_to_account_last'];
        $this->app_obj->call_set_contact_name();
        return true;
    }

    /**
     * @param $call_array
     * 发送操作菜单
     */
    private function _sendAppMune($call_array){
        $this->to_user_id = $call_array['to_id'];
        if($this->call_type == CallRecord::Record_Type_none){    //联系电话呼叫完
            if(empty($this->_getCallNumbers(CallRecord::Record_Type_emergency ,[]))){
                $this->call_type  = CallRecord::Record_Type_emergency;
            }
        }
        $this->app_obj->sendCallButton($this->call_type,
            $call_array['app_to_account_id'],
            $call_array['to_id'] ,
            $this->app_obj->last_contact_name, //$call_array['to_nickname'],
            $this->app_obj->first_contact_name, //$call_array['from_nickname'],
            $call_array['app_from_account_id']
        ); //发送继续呼叫按钮
    }

    /**
     * @param $cacheKey
     * @return array
     * 获取redis 键值对
     */
    private function _redisGetVByK($cacheKey){

        $cache_keys = Yii::$app->redis->hkeys($cacheKey);
        $catch_vals = Yii::$app->redis->hvals($cacheKey);
        Yii::$app->redis->del($cacheKey);
        return array_combine($cache_keys , $catch_vals);

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
        $this->call_type        = $call_array['call_type'];
        $this->app_type         = $call_array['app_type'];
        if( $this->call_num = Yii::$app->redis->llen($list_key) <= 0 ){         //队列空 发送重新呼叫按钮
            $this->_sendAppMune($call_array);
            return true;
        }

        $send = Yii::$app->redis->rpop($list_key);
        $send = json_decode($send ,true);

        $this->third->to            = $send['to'];
        $this->third->messageText   = $send['text'];
        $this->third->from          = $send['from'];
        $this->third->messageType   = $send['message_type'];
        $this->third->Language      = $this->app_obj->language;

        $this->call_type            = $send['call_type'];

        $call_array['nickname'] =  $send['nickname'];
        $this->app_obj->continueCall($this->call_type ,$call_array );
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
        $callRecord->type = $data['call_type'];                        //呼叫的类型 紧急联系人呼叫 ？ 正常呼叫？
        $callRecord->save();
    }




}