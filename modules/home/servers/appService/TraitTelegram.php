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

trait  TraitTelegram {

    public $first_contact_name;
    public $last_contact_name;

    public function call_set_name(){
        $this->first_contact_name = $this->telegramFirstName;
        $this->last_contact_name = $this->telegramContactFirstName;
    }
    public function call_set_contact_name(){
        $this->telegramFirstName = $this->first_contact_name;
        $this->telegramContactFirstName  = $this->last_contact_name;
    }
    /**
     *拨打电话失败 消息推送
     * @param  int $type
     * @param  string $telegram_name  用户app名称
     * @param  string $anwser         应答状态
     * @return bool
     */
    public function sendCallFailed($telegram_name,$anwser){
        //$this->tlanguage = $this->language;
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->_CallAnwserText($anwser , $telegram_name),
            ];
        $this->sendTelegramData();
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
//        $data['to_account'] = $this->telegramContactLastName.$this->telegramContactFirstName;
        //$this->tlanguage = $this->language;
        if($type == CallRecord::Record_Type_none){
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'的其他电话，请稍候！'),
            ];
        }elseif($type == CallRecord::Record_Type_emergency){
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$this->last_contact_name.'的紧急联系人:'.$data['nickname'].'，请稍候！'),
            ];
        }
        $this->sendTelegramData();
        return true;
    }

    /**
     *呼叫流程开始提示
     */
    public function startCall($type , Array $data = []){
        //$this->tlanguage = $this->language;
        if(empty($data['to_account'])){
            $data['to_account'] = $this->telegramContactLastName.$this->telegramContactFirstName;
        }
        if($type == CallRecord::Record_Type_none){
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'，请稍候！'),
            ];
        }elseif($type == CallRecord::Record_Type_emergency){
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->translateLanguage('正在尝试呼叫'.$data['to_account'].'的紧急联系人:'.$data['nickname'].'，请稍候！')
            ];
        }
        $this->sendTelegramData();
        return true;
    }

    /**
     *呼叫异常 提示
     */
    public function exceptionCall(){
        //$this->tlanguage = $this->language;
        $this->_Del_Rate_Call();
        $this->sendData = [
            'chat_id' =>$this->telegramUid,
            'text' => $this->translateLanguage('呼叫异常，请稍后再试!')
        ];
        $this->sendTelegramData();
    }

    /**
     * @param $type
     * 打电话成功 消息推送
     * @return bool
     */
    public function sendCallSuccess($telegram_name){
        //$this->tlanguage = $this->language;
        $this->_Del_Rate_Call();
        $this->sendData = [
            'chat_id' =>$this->telegramUid,
            'text' => $this->translateLanguage('呼叫'.$telegram_name.'成功!'),
        ];
        $this->sendTelegramData();
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
        $this->setWebhook();
        $this->_Del_Rate_Call();
        if($type == CallRecord::Record_Type_none){              //联系电话呼叫完  发送拨打紧急联系人按钮
            $callback = [
                $this->callUrgentCallbackDataPre,
                $appCalledUid,
                $calledUserId,
                $calledAppName,
                $callAppName,
            ];
            $text = Yii::t('app/model/nexmo', 'Whether to call an emergency contact ?', array(), $this->language);
            $keyBoard = [
                [
                    [
                        'text' => Yii::t('app/model/nexmo', 'Yes', array(), $this->language),
                        'callback_data' => implode('-', $callback),
                    ]
                ]
            ];
            $this->sendData = [
                'chat_id' => $appCallUid,
                'text' => $text,
                'reply_markup' => [
                    'inline_keyboard' => $keyBoard,
                ],
            ];

        }elseif($type == CallRecord::Record_Type_emergency){    //紧急联系人呼叫完  发送重新拨打按钮

            $callback = [
                $this->callCallbackDataPre,
                $appCalledUid,
                $calledAppName,
                $callAppName,

            ];
            $text = Yii::t('app/model/nexmo', 'Whether to call again ?', array(), $this->language);
            $keyBoard = [
                [
                    [
                        'text' => Yii::t('app/model/nexmo', 'Re-call', array(), $this->language),
                        'callback_data' => implode('-', $callback),
                    ]
                ]
            ];
            $this->sendData = [
                'chat_id' => $appCallUid,
                'text' => $text,
                'reply_markup' => [
                    'inline_keyboard' => $keyBoard,
                ],
            ];
        }

        $this->sendTelegramData();
        return true;
    }



    /**
     * 呼叫telegram账号.
     */
    public function call($call_type)
    {
        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (!$res) {
            // 发送验证码，完成绑定.
            return $this->sendBindCode();
        } elseif ($this->telegramUid == $this->telegramContactUid) {
            $this->language = $res->language;
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->getCompleteText(),
            ];
            $this->sendTelegramData();
            return $this->errorCode['success'];
        }

        $this->callPersonData = $res;
        $this->language = $this->callPersonData->language;

        $user = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if ($user) {
            if(!$this->_Rate_call()){
                return $this->errorCode['success'];
            }
            // 开始操作.
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->getStartText(),
            ];
            $this->sendTelegramData();

            $this->calledPersonData = $user;
            $nickname = $this->telegramContactFirstName;
            if (empty($nickname)) {
                $nickname = !empty($user->nickname) ? $user->nickname : '他/她';
            }

            // 黑名单检查.
            $res = $this->blackList();
            if ($res) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => $this->translateLanguage('您在'.$nickname.'的黑名单列表内, 不能呼叫!'),
                ];
                $this->sendTelegramData();
                return $this->errorCode['success'];
            }

            // 白名单检查.
            if ($this->calledPersonData->whitelist_switch == 1) {
                $res = $this->whiteList();
                if (!$res) {
                    $this->sendData = [
                        'chat_id' => $this->telegramUid,
                        'text' => $this->translateLanguage('您不在'.$nickname.'的白名单列表内, 不能呼叫!'),
                    ];
                    $this->sendTelegramData();
                    return $this->errorCode['success'];
                }
            }
            // 呼叫限制检查.
            $res = $this->callLimit();
            if (!$res['status']) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => $this->translateLanguage('呼叫'.$nickname.'失败! '.$res['message']),
                ];
                $this->sendTelegramData();
                return $this->errorCode['success'];
            }
            $service = TTSservice::init(\app\modules\home\servers\TTSservice\Sinch::class);
            $service->from_user_id = $this->callPersonData->id;
            $service->to_user_id = $this->calledPersonData->id;

            $tmp_tlanguage = $this->tlanguage;
            $tmp_llanguage = $this->llanguage;
            $this->setLanguage($this->calledPersonData->language);
            if($call_type == CallRecord::Record_Type_none){
                $service->messageText = $this->translateLanguage($this->telegramFirstName.'呼叫您上线').'telegram';
            }else{
                $service->messageText = $this->translateLanguage('请转告'.$this->calledPersonData->telegram_name.'上线').'telegram';
            }
            $this->tlanguage = $tmp_tlanguage;
            $this->llanguage = $tmp_llanguage;

            $service->messageType = 'TTS';
            $service->app_type ='telegram';
            $service->sendMessage($call_type , $this);
            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->telegramContactLastName.$this->telegramContactFirstName.$this->getIsNotMemberText(),
            ];
            $this->sendTelegramData();
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

        $key = $this->telegramUid.'_call_'.$this->telegramContactUid;
        if(Yii::$app->redis->exists($key)){
            return false;
        }else{
            Yii::$app->redis->set($key , 1);
            Yii::$app->redis->expire($key , 30*60);
        }
        return true;
    }

    private function _Del_Rate_Call(){
        $key = $this->telegramUid.'_call_'.$this->telegramContactUid;
        if(Yii::$app->redis->exists($key)){
            Yii::$app->redis->del($key);
        }
        return true;
    }
}