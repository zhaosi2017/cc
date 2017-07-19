<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/18
 * Time: 下午5:50
 */
namespace app\modules\home\servers\appService;


use app\modules\home\models\CallRecord;

trait  TraitTelegram {

    /**
     *拨打电话失败 消息推送
     * @param  int $type
     * @param string $telegram_name  用户app名称
     * @param string $anwser         应答状态
     * @return bool
     */
    public function sendCallFailed($type, $telegram_name,$anwser){

        if($type == CallRecord::Record_Type_none){              //联系电话呼叫失败
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->_CallAnwserText($anwser , $telegram_name),
            ];
        }elseif($type == CallRecord::Record_Type_emergency){    //紧急联系人呼叫失败
            $this->sendData = [
                'chat_id' =>$this->telegramUid,
                'text' => $this->_CallAnwserText($anwser , $telegram_name),
            ];

        }else{
            return true;
        }
        $this->sendTelegramData();
        return true;
    }

    /**
     * @param $type
     * 打电话成功 消息推送
     * @return bool
     */
    public function sendCallSuccess($telegram_name){

        $this->sendData = [
            'chat_id' =>$this->telegramUid,
            'text' => '呼叫'.$telegram_name.'成功',
        ];
        $this->setWebhook();
        $this->sendTelegramData();
        return true;
    }

    /**
     * @param $type
     * @param $appCalledUid   主叫id
     * @param $calledUserId   被叫id
     * @param $callAppName    主叫昵称
     * @param $calledAppName  被叫昵称
     * @return bool
     */
    public function sendCallButton($type, $appCalledUid, $calledUserId,$callAppName,$calledAppName){
        $this->setWebhook();

        if($type == CallRecord::Record_Type_none){              //联系电话呼叫完  发送拨打紧急联系人按钮
            $callback = [
                $this->callUrgentCallbackDataPre,
                $appCalledUid,
                $calledUserId,
                $callAppName,
                $calledAppName
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
                'chat_id' => $appCalledUid,
                'text' => $text,
                'reply_markup' => [
                    'inline_keyboard' => $keyBoard,
                ],
            ];

        }elseif($type == CallRecord::Record_Type_emergency){    //紧急联系人呼叫完  发送重新拨打按钮

            $callback = [
                $this->callCallbackDataPre,
                $appCalledUid,
                $callAppName,
                $calledAppName
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
                'chat_id' => $appCalledUid,
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
     * 呼叫应答 对应的文字消息
     */
    private function _CallAnwserText(  $anwser , $calledName){
            if($anwser == 'timeout') return '呼叫'.$calledName.'失败, 暂时无人接听!';
            if($anwser == 'answered') return '呼叫'.$calledName.'成功!';
            if($anwser == 'failed') return '呼叫'.$calledName.'无法接通!';
            if($anwser == 'unanwsered') return '呼叫'.$calledName.'暂时无人接听!';
            if($anwser == 'busy') return '呼叫的用户忙!';
    }




}