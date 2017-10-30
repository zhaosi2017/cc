<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/18
 * Time: 下午5:50
 */
namespace app\modules\home\servers\appService;


use app\modules\home\models\CallRecord;
use app\modules\home\models\UserGentContact;
use app\modules\home\models\UserPhone;
use Yii;
use yii\db\Exception;
use app\modules\home\models\User;
use app\modules\home\servers\TTSservice\TTSservice;

trait  TraitPotato {

    public $first_contact_name;
    public $last_contact_name;

    public function call_set_name(){
        $this->first_contact_name = $this->potatoSendFirstName;
        $this->last_contact_name = $this->potatoContactFirstName;
    }
    public function call_set_contact_name(){
        $this->potatoSendFirstName = $this->first_contact_name;
        $this->potatoContactFirstName  = $this->last_contact_name;
    }
    /**
     *拨打电话失败 消息推送
     * @param  int $type
     * @param  string  $name          用户app名称
     * @param  string $anwser         应答状态
     * @return bool
     */
    public function sendCallFailed($name,$anwser){
        //$this->tlanguage = $this->language;
        $this->sendData = [
            'chat_type'=>1,
            'chat_id' =>(int)$this->potatoUid,
            'text' => $this->_CallAnwserText($anwser , $name),
        ];
        $this->setWebhook($this->webhookUrl);
        $this->sendPotatoData();
        return true;
    }
    /**
     * 呼叫应答 对应的文字消息
     */
    private function _CallAnwserText(  $anwser , $calledName){

        if($anwser == 'timeout') return $this->translateLanguage('呼叫'.$calledName.'失败, 暂时无人接听!');
        if($anwser == 'answered') return $this->translateLanguage('呼叫'.$calledName.'成功!');
        if($anwser == 'failed') return $this->translateLanguage('呼叫'.$calledName.'失败!');
        if($anwser == 'unanwsered') return $this->translateLanguage('呼叫'.$calledName.'失败,暂时无人接听!');
        if($anwser == 'busy') return $this->translateLanguage('呼叫的用户忙!');
    }

    /**
     * 每次呼叫开始提示
     * @param $type
     * @param array $data
     * @return bool
     */
    public function continueCall($type , Array $data = []){
        //$this->tlanguage = $this->language;
//        if(isset($data['link']) && !empty($data['link'])){
//            $this->sendData = [
//                'chat_type'=>1,
//                'chat_id' =>(int)$this->potatoUid,
//                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'的其他客优账号:'.$data['nickname'].'，请稍候！'),
//            ];
//            $this->setWebhook($this->webhookUrl);
//            $this->sendPotatoData();
//            return true;
//        }
        if($type == CallRecord::Record_Type_none){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'的其他电话，请稍候！'),
            ];
        }elseif($type == CallRecord::Record_Type_emergency){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$this->last_contact_name.'的紧急联系人:'.$data['nickname'].'，请稍候！'),
            ];
        }
        $this->setWebhook($this->webhookUrl);
        $this->sendPotatoData();
        return true;
    }

    /**
     *呼叫流程开始提示
     */
    public function startCall($type , Array $data = []){
        if(!empty($data['link_user'])){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('开始呼叫对方的其他客优账号！(共'.$data['count'].'部)')
            ];
            $this->setWebhook($this->webhookUrl);
            $this->sendPotatoData();
            return true;
        }
        if(empty($data['to_account'])){
            $data['to_account'] = $this->potatoContactFirstName;
        }
        if($type == CallRecord::Record_Type_none){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'，请稍候！(共'.$data['count'].'部)'),
            ];
        }elseif($type == CallRecord::Record_Type_emergency){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'的紧急联系人:'.$data['nickname'].'，请稍候！(共'.$data['count'].'位)')
            ];
        }
        $this->setWebhook($this->webhookUrl);
        $this->sendPotatoData();
        return true;
    }

    /**
     *呼叫异常 提示
     */
    public function exceptionCall(){
       // $this->tlanguage = $this->language;
        //$this->_Del_Rate_Call();
        $this->sendData = [
            'chat_type'=>1,
            'chat_id' =>(int)$this->potatoUid,
            'text' => $this->translateLanguage('呼叫异常，请稍后再试!')
        ];
        $this->setWebhook($this->webhookUrl);
        $this->sendPotatoData();
    }

    /**
     * @param $type
     * 打电话成功 消息推送
     * @return bool
     */
    public function sendCallSuccess($name){
       // $this->tlanguage = $this->language;
       // $this->_Del_Rate_Call();
        $this->sendData = [
            'chat_type'=>1,
            'chat_id' =>(int)$this->potatoUid,
            'text' => $this->translateLanguage('呼叫'.$name.'成功!'),
        ];
        $this->setWebhook($this->webhookUrl);
        $this->sendPotatoData();
        return true;
    }

    /**
     * 没有可用的联系电话号码
     * @param $name
     * @return bool
     */
    public function sendCallNoNumber($name){

        $this->sendData = [
            'chat_type'=>1,
            'chat_id' =>(int)$this->potatoUid,
            'text' => $this->translateLanguage($name.'没有可用的联系电话!'),
        ];
        $this->setWebhook($this->webhookUrl);
        $this->sendPotatoData();
        return true;
    }

    /**
     * @param $type
     * @param $appCalledUid   主叫 tg_id
     * @param $calledUserId   被叫 user_id
     * @param $callAppName    被叫第一个名
     * @param $calledAppName  被叫姓
     * @param  $link_user     关联用户标志
     * @return bool
     */
    public function sendCallButton($type, $appCalledUid, $calledUserId,$callAppName,$calledAppName ,$appCallUid , $link_user=false){


        if($type == CallRecord::Record_Type_none ){              //联系电话呼叫完  发送拨打紧急联系人按钮
            $callback = [
                $this->callUrgentCallbackDataPre,
                $appCalledUid,
                $calledUserId,
                $calledAppName,
                $callAppName,
                time(),
            ];
            //$text = Yii::t('app/model/nexmo', 'Whether to call an emergency contact ?', array(), $this->language);
            $keyBoard = [
                [
                    [
                        'type' => 0,
                        'text' => "呼叫紧急联系人",
                        'data' => implode('-', $callback),
                    ]
                ]
            ];
            if($link_user){  //存在关联用的时候
                $callback1 = [
                    $this->callUrgentCallbackDataPre,
                    $appCalledUid,
                    $calledUserId,
                    $calledAppName,
                    $callAppName,
                    time(),
                    $link_user
                ];
                $keyBoard[0][] = [
                    'type' => 0,
                    'text' =>"呼叫关联用户", //Yii::t('app/model/nexmo', 'No', array(), $this->language),
                    'data' => implode('-', $callback1),
                ];
            }
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => (int)$appCallUid,
                'text' => "使用其他联系方式",
                'inline_markup' => $keyBoard,
            ];

        }elseif($type == CallRecord::Record_Type_emergency){    //紧急联系人呼叫完  发送重新拨打按钮

            $callback = [
                $this->callCallbackDataPre,
                $appCalledUid,
                $calledAppName,
                $callAppName,
                time()
            ];
            $text = Yii::t('app/model/nexmo', 'Whether to call again ?', array(), $this->language);
            $keyBoard = [
                [
                    [
                        'type' => 0,
                        'text' => Yii::t('app/model/nexmo', 'Re-call', array(), $this->language),
                        'data' => implode('-', $callback),
                    ]
                ]
            ];
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => (int)$appCallUid,
                'text' => $text,
                'inline_markup' => $keyBoard,
            ];
        }
        $this->setWebhook($this->menuWebHookUrl);
        $this->sendPotatoData();
        return true;
    }



    /**
     * 呼叫potato账号.
     */
    public function call($call_type , Array $data = [])
    {
        $link = $data['link']?true:false;  //关联用户标志

//        if(!$this->_Rate_call_Message($data)){
//
//            return $this->errorCode['success'];
//        }
        $this->setWebhook($this->webhookUrl);
        $res = User::findOne(['potato_user_id' => $this->potatoUid]);
        if (!$res) {
            // 发送验证码，完成绑定.
            return $this->sendBindCode();
        } elseif ($this->potatoUid == $this->potatoContactUid) {
            $this->language = $res->language;
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' => (int)$this->potatoUid,
                'text' => $this->getCompleteText(),
            ];
            $this->sendPotatoData();
            return $this->errorCode['success'];
        }

        $this->callPersonData = $res;
        $this->language = $this->callPersonData->language;


        $user = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        if ($user) {
            $this->calledPersonData = $user;
            if(!$this->_check_Phone($call_type)){
                return $this->errorCode['success'];
            }
            // 开始操作.
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' => (int)$this->potatoUid,
                'text' => $this->getStartText(),
            ];
            $this->sendPotatoData();

            $nickname = $this->potatoContactFirstName;
            if (empty($nickname)) {
                $nickname = !empty($user->nickname) ? $user->nickname : '他/她';
            }

            // 黑名单检查.
            $res = $this->blackList();
            if ($res) {
                $this->sendData = [
                    'chat_type'=>1,
                    'chat_id' => (int)$this->potatoUid,
                    'text' => $this->translateLanguage('您在'.$nickname.'的黑名单列表内, 不能呼叫!'),
                ];
                $this->sendPotatoData();
                return $this->errorCode['success'];
            }

            // 白名单检查.
            if ($this->calledPersonData->whitelist_switch == 1) {
                $res = $this->whiteList();
                if (!$res) {
                    $this->sendData = [
                        'chat_type'=>1,
                        'chat_id' =>(int)$this->potatoUid,
                        'text' => $this->translateLanguage('您不在'.$nickname.'的白名单列表内, 不能呼叫!'),
                    ];
                    $this->sendPotatoData();
                    return $this->errorCode['success'];
                }
            }
            // 呼叫限制检查.
            $res = $this->callLimit();
            if (!$res['status']) {
                $this->sendData = [
                    'chat_type'=>1,
                    'chat_id' => (int)$this->potatoUid,
                    'text' => $this->translateLanguage('呼叫'.$nickname.'失败! ').' '.$res['message'],
                ];
                $this->sendPotatoData();
                return $this->errorCode['success'];
            }

            $tmp_tlanguage = $this->tlanguage;
            $tmp_llanguage = $this->llanguage;
            $this->setLanguage($this->calledPersonData->language);
            $service = TTSservice::init(\app\modules\home\servers\TTSservice\Sinch::class);
            $service->from_user_id = $this->callPersonData->id;
            $service->to_user_id = $this->calledPersonData->id;

            // 自定义语音内容.
            $voiceCacheKey = 'cc_voice_'.$this->callPersonData->id;
            $voiceContent = '';
            if (Yii::$app->redis->exists($voiceCacheKey)) {
                $voiceContent = Yii::$app->redis->get($voiceCacheKey);
                Yii::$app->redis->del($voiceCacheKey);
            }
            if (!empty($voiceContent)) {
                $service->messageText = $this->translateLanguage($voiceContent);
            } else {
                if ($call_type == CallRecord::Record_Type_none) {
                    $callName = empty($this->callPersonData->nickname) ? $this->potatoSendFirstName : $this->callPersonData->nickname;
                    $service->messageText = $this->translateLanguage($callName . ' 呼叫您上线') . ' potato';
                } else {
                    $callName = empty($user->nickname) ? $this->potatoContactFirstName : $user->nickname;
                    $service->messageText = $this->translateLanguage('请转告 ' . $callName . ' 上线') . ' potato';
                }
            }
            $this->tlanguage = $tmp_tlanguage;
            $this->llanguage = $tmp_llanguage;

            $service->messageType = 'TTS';
            $service->app_type ='potato';
            $service->Language = $this->llanguage;

            $service->sendMessage($call_type , $this  , $link);
            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' => (int)$this->potatoUid,
                'text' => $this->potatoContactFirstName.$this->getIsNotMemberText(),
            ];
            $this->sendPotatoData();
            return $this->errorCode['success'];
        }
    }


    private function _check_Phone($call_type){
            $phone_numbers = UserPhone::findAll(['user_id'=>$this->calledPersonData->id]);
            $contact_numbers = UserGentContact::findAll(['user_id'=>$this->calledPersonData->id]);
            if(empty($phone_numbers) && empty($contact_numbers)){   //无可用的联系方式
                $this->sendCallNoNumber($this->potatoContactFirstName);
                return false;
            }
            if(empty($phone_numbers) && !empty($contact_numbers) && $call_type == CallRecord::Record_Type_none){       //没有联系电话
                $this->sendCallButton(  CallRecord::Record_Type_none,
                                        $this->potatoContactUid,
                                        $this->calledPersonData->id,
                                        $this->potatoContactFirstName ,
                                        $this->potatoSendFirstName,
                                        $this->potatoUid);
                return false;
            }
            return true;
    }


    /**
     *
     *监测用户a-》b的通话是否在进行中 如
     *如果正在进行中 则不响应本次回调
     *检测锁
     */
    private function _Rate_call(){

        $key = $this->potatoUid.'_call_potato_'.$this->potatoContactUid;
        if(Yii::$app->redis->exists($key)){
            return false;
        }else{
            Yii::$app->redis->set($key , 1);
            Yii::$app->redis->expire($key , 30*60);
        }
        return true;
    }

    /**
     * @return bool
     * 删除锁
     */
    private function _Del_Rate_Call(){
        $key = $this->potatoUid.'_call_potato_'.$this->potatoContactUid;
        if(Yii::$app->redis->exists($key)){
            Yii::$app->redis->del($key);
        }
        return true;
    }

    /**
     * 加消息锁 阻止滥用的回调 呼叫事件
     */
    private function _Rate_call_Message(Array $data = []){

        $key = $this->potatoUid.'_messagerate_potato';
        if(Yii::$app->redis->exists($key)){
            $message_id = Yii::$app->redis->get($key);
            if((int)$message_id >= (int)$data['message_id'] ){
                return false;
            }
        }
        Yii::$app->redis->set($key, (int)$data['message_id']);
        Yii::$app->redis->expire($key , 24*60*60);
        return true;
    }

}