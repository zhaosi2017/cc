<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/18
 * Time: 下午5:50
 */
namespace app\modules\home\servers\appService;


use app\modules\home\models\CallRecord;
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
        //$this->tlanguage = $this->language;
        if(empty($data['to_account'])){
            $data['to_account'] = $this->potatoContactFirstName;
        }
        if($type == CallRecord::Record_Type_none){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'，请稍候！'),
            ];
        }elseif($type == CallRecord::Record_Type_emergency){
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' =>(int)$this->potatoUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'的紧急联系人:'.$data['nickname'].'，请稍候！')
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
        $this->_Del_Rate_Call();
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
        $this->_Del_Rate_Call();
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
     * @param $type
     * @param $appCalledUid   主叫 tg_id
     * @param $calledUserId   被叫 user_id
     * @param $callAppName    被叫第一个名
     * @param $calledAppName  被叫姓
     * @return bool
     */
    public function sendCallButton($type, $appCalledUid, $calledUserId,$callAppName,$calledAppName ,$appCallUid){

        $this->_Del_Rate_Call();
        if($type == CallRecord::Record_Type_none){              //联系电话呼叫完  发送拨打紧急联系人按钮
            $callback = [
                $this->callUrgentCallbackDataPre,
                $appCalledUid,
                $calledUserId,
                $calledAppName,
                $callAppName,
                time()
            ];
            $text = Yii::t('app/model/nexmo', 'Whether to call an emergency contact ?', array(), $this->language);
            $keyBoard = [
                [
                    [
                        'type' => 0,
                        'text' => Yii::t('app/model/nexmo', 'Yes', array(), $this->language),
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
    public function call($call_type)
    {
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
            if(!$this->_Rate_call()){

                return $this->errorCode['success'];
            }
            // 开始操作.
            $this->sendData = [
                'chat_type'=>1,
                'chat_id' => (int)$this->potatoUid,
                'text' => $this->getStartText(),
            ];
            $this->sendPotatoData();
            $this->calledPersonData = $user;
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
                    'text' => $this->translateLanguage('呼叫'.$nickname.'失败! '.$res['message']),
                ];
                $this->sendPotatoData();
                return $this->errorCode['success'];
            }
            $service = TTSservice::init(\app\modules\home\servers\TTSservice\Sinch::class);
            $service->from_user_id = $this->callPersonData->id;
            $service->to_user_id = $this->calledPersonData->id;
            if($call_type == CallRecord::Record_Type_none){
                $service->messageText = $this->translateLanguage($this->potatoSendFirstName.'呼叫您上线').'potato';
            }else{
                $service->messageText = $this->translateLanguage('请转告'.$this->potatoContactFirstName.'上线').'potato';
            }
            $service->messageType = 'TTS';
            $service->app_type ='potato';
            $service->Language = $this->llanguage;
            $service->sendMessage($call_type , $this);
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
    /**
     *
     *监测用户a-》b的通话是否在进行中 如
     *如果正在进行中 则不响应本次回调
     *
     */
    private function _Rate_call(){

        $key = $this->potatoUid.'_call_'.$this->potatoContactUid;
        if(Yii::$app->redis->exists($key)){
            return false;
        }else{
            Yii::$app->redis->set($key , 1);
            Yii::$app->redis->expire($key , 30*60);
        }
        return true;
    }

    private function _Del_Rate_Call(){
        $key = $this->potatoUid.'_call_'.$this->potatoContactUid;
        if(Yii::$app->redis->exists($key)){
            Yii::$app->redis->del($key);
        }
        return true;
    }

}