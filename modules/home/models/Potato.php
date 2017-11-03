<?php
namespace app\modules\home\models;

use app\modules\home\servers\appService\AppService;
use app\modules\home\servers\appService\TraitPotato;
use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;

class Potato extends Model
{
    use TraitPotato;

    const CODE_LENGTH = 5;

    private $potatoText = "Operation menu.";
    private $startText = 'Start the operation, please wait later.';
    private $wellcomeText = 'welcome';
    private $keyboardText = 'Share your contact card';
    private $firstText = '/start';
    //正式 callu
    private $webhookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $callbackAnswerUrl = 'https://bot.potato.im:5423/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendCallbackAnswer';
    private $menuWebHookUrl = 'https://bot.potato.im:5423/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendInlineMarkupMessage';

    //测试 callme
//    private $webhookUrl = 'http://bot.potato.im:4235/8013511:mCpb1avXHD74NLb7K6YT9AJd/sendTextMessage';
//    private $callbackAnswerUrl = 'https://bot.potato.im:5423/8013511:mCpb1avXHD74NLb7K6YT9AJd/sendCallbackAnswer';
//    private $menuWebHookUrl = 'https://bot.potato.im:5423/8013511:mCpb1avXHD74NLb7K6YT9AJd/sendInlineMarkupMessage';

    private $translateUrl = "https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk";
    private $tlanguage = 'zh-CN';
    private $llanguage = 'zh-CN';
    private $repeat = 3;
    private $voice = 'male';
    private $rateKey = 'rateKey_potato_';
    private $rateExpireTime = 10;
    private $rateText = 'Operation too fast, please try again later!';
    // 是否是紧急呼叫.
    private $isUrgentCall = 0;

    private $code;
    private $bindCode;
    private $potatoUid;
    private $keyboard;
    private $shareRequestType = 4;
    private $callBackRequestType = 2;
    private $callUrgentCallbackDataPre = 'cc_call_urgent';
    private $callCallbackDataPre = 'cc_call';
    private $whiteCallbackDataPre = 'cc_white';
    private $unwhiteCallbackDataPre = 'cc_unwhite';
    private $whitelistSwitchCallbackDataPre = 'cc_whiteswitch';
    private $unwhitelistSwitchCallbackDataPre = 'cc_unwhiteswitch';
    private $blackCallbackDataPre = "cc_black";
    private $unblackCallbackDataPre = "cc_unblack";
    private $potatoContactUid;
    private $potatoContactPhone = '';
    private $potatoContactFirstName = '';
    private $potatoContactLastName = '';
    private $potatoSendFirstName = '';
    private $potatoSendLastName = '';
    private $callPersonData;
    private $calledPersonData;
    private $whiteText = 'Join Whitelist';
    private $unwhiteText = 'Unlock the whitelist';
    private $blackText = "Join blacklist";
    private $unblackText = "Unlock the blacklist";
    private $successText = 'success';
    private $failureText = 'failure';
    private $callText = "call";
    private $whiteSwitchText = 'Open the whitelist';
    private $unwhiteSwitchText = 'Close the whitelist';
    private $bindRecommendText = "[Please enter the verification code on the callu platform to complete the binding operation!]";
    private $menuShareText = "Please share your own contact card to the robot, complete the binding operation.";
    private $completeText = "You have completed the binding operation.";
    private $isNotMemberText = 'is not a member of our system, can not perform the operation.';
    private $exceedText = 'The number of times the call has exceeded the limit set by he.';
    private $codeEmptyText = 'The verification code is empty.';
    private $codeErrorText = 'Verification code error.';
    private $enableNoMemberText = "You are not a member of our system and can not perform this operation.";
    private $menuNoMemberText = "He is not a member of our system and you can not perform this operation.";
    private $joinAlreadyText = "Already in the white list.";
    private $joinWhiteListSuccess = "Join whitelist successfully.";
    private $joinWhiteListFailure = "Join whitelist failed.";
    private $joinRecommendText = "Has already added you to the whitelist, you can also click the button below to add him to your whitelist.";
    private $unbindSuccessText = "Cancel the whitelist successfully.";
    private $unbindFailureText = "Cancel the whitelist failed.";
    private $unbindNotText = "Not in the white list.";
    private $enableWhiteText = "White List has been turned on.";
    private $enableWhiteSuccessText = "Open white list function successfully.";
    private $enableWhiteFailureText = "Open whitelist failed.";
    private $disableWhiteText = "Has closed the whitelist function.";
    private $disableWhiteSuccessText = "Close White List Function successfully.";
    private $disableWhiteFailureText = "Close whitelist failed.";
    private $joinBlackListAreadyText = "Already in the blacklist.";
    private $joinBlackListSuccessText = "Add to Blacklist successfully.";
    private $joinBlackListFailureText = "Add to Blacklist failed.";
    private $unlockBlackListSuccessText = "Unlock the blacklist successfully.";
    private $unlockBlackListFailureText = "Unlock the blacklist failed.";
    private $notInBlackList = "Not in blacklist.";
    private $bindVerifyCode = "Your verification code is";

    private $sendData;
    private $errorCode = [
        'success' => 200,
        'error' => 400,
        'invalid_operation' => 401,
        'not_yourself' => 402,
        'exist' => 403,
        'noexist' => 404,
        'emptyuid' => 405,
    ];
    private $errorMessage = [
        'success' => '成功',
        'error' => '失败',
        'invalid_operation' => '无效的操作',
        'not_yourself' => '不是自己的名片',
        'exist' => '已经绑定过',
        'noexist' => '没有查询到此人',
        'emptyuid' => '发送人不能为空',
    ];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['bindCode'], 'string']
        ];
    }

    /**
     * 设置webhook
     */
    public function setWebhook($value)
    {
        $this->webhookUrl = $value;
    }

    /**
     * 是否紧急呼叫.
     */
    public function setIsUrgentCall($value)
    {
        $this->isUrgentCall = $value;
    }

    /**
     * @param $value
     */
    public function setPotatoUid($value)
    {
        $this->potatoUid = $value;
    }

    /**
     * @param $value
     */
    public function setPotatoContactUid($value)
    {
        $this->potatoContactUid = $value;
    }

    public function getPotatoContactUid(){

        return  $this->potatoContactUid;
    }

    /**
     * @param $value
     */
    public function setPotatoContactPhone($value)
    {
        $this->potatoContactPhone = $value;
    }

    /**
     * 设置名.
     *
     * @param string $value 名.
     */
    public function setPotatoContactFirstName($value)
    {
        $this->potatoContactFirstName = $value;
    }

    /**
     * 设置姓.
     *
     * @param string $value 名.
     */
    public function setPotatoContactLastName($value)
    {
        $this->potatoContactLastName = $value;
    }

    /**
     * @param $value
     */
    public function setPotatoSendFirstName($value)
    {
        $this->potatoSendFirstName = $value;
    }

    /**
     * @param $value
     */
    public function setPotatoSendLastName($value)
    {
        $this->potatoSendLastName = $value;
    }

    /**
     * @param $userId
     * @param $inlineMessageId
     *
     * @return json
     */
    public function sendCallbackAnswer($userId, $inlineMessageId)
    {
        $this->sendData = [
            'user_id' => $userId,
            'inline_message_id' => $inlineMessageId,
        ];

        return $this->sendPotatoData($this->callbackAnswerUrl);
    }

    /**
     * 获取验证码.
     *
     * @return string
     */
    public function makeCode()
    {
        return rand(1000,9999);
    }

    /**
     * 设置验证码.
     */
    public function setCode()
    {
        $dealData = [
            Yii::$app->params['potato_pre'],
            $this->potatoContactUid,
            $this->potatoContactPhone,
            $this->potatoContactLastName.$this->potatoContactFirstName,
        ];

        $dealData = implode('-', $dealData);
        $this->code = $this->makeCode();
        $potatoData = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['potato']));
        // 验证码过期时间半小时.
        Yii::$app->redis->setex($this->code, 30*60, $potatoData);
        $this->code = $this->getBindVerifyCode().' '.$this->code;
    }

    /**
     * @param $value
     */
    public function setBindCode($value)
    {
        $this->bindCode = strtolower(trim($value));
    }

    /**
     * @param $value
     */
    public function setSendData($value)
    {
        $this->sendData = $value;
    }

    /**
     * @param $value.
     */
    public function setCalledPersonData($value)
    {
        $this->calledPersonData = $value;
    }

    /**
     * @param $value
     */
    public function setCallPersonData($value)
    {
        $this->callPersonData = $value;
    }

    /**
     * 设置语言.
     */
    public function setLanguage($value)
    {
        if (!stripos($value, '-')) {
            switch ($value) {
                case 'zh';
                    $this->llanguage = 'zh-CN';
                default;
                    break;
            }
        } else {
            $this->llanguage = $value;
        }

        // tlanguage语言设置.
        if (!stripos($value, 'zh')) {
            $language = explode('-', $value);
            $this->tlanguage = $language[0];
        } else {
            $this->tlanguage = $value;
        }
    }

    /**
     * 分享联系人名片请求方式.
     *
     * @return int
     */
    public function getShareRequestType()
    {
        return $this->shareRequestType;
    }

    /**
     * @return string
     */
    public function getQueryText()
    {
        return $this->queryText;
    }

    /**
     * @return string
     */
    public function getWebhook()
    {
        return $this->webhookUrl;
    }

    /**
     * @return mixed
     */
    public function getMenuWebHook()
    {
        return $this->menuWebHookUrl;
    }

    /**
     * @return string
     */
    public function getCallText()
    {
        return Yii::t('app/model/potato', $this->callText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getBindText()
    {
        return $this->bindText;
    }

    /**
     * @return int
     */
    public function getCallBackRequestType()
    {
        return $this->callBackRequestType;
    }

    /**
     * @return mixed
     */
    public function getCallUrgentCallbackDataPre()
    {
        return $this->callUrgentCallbackDataPre;
    }

    /**
     * @return mixed
     */
    public function getPotatoUid()
    {
        return $this->potatoUid;
    }

    /**
     * @return mixed
     */
    public function getPotatoContactFirstName()
    {
        return $this->potatoContactFirstName;
    }

    /**
     * @return mixed
     */
    public function getPotatoContactLastName()
    {
        return $this->potatoContactLastName;
    }

    /**
     * @return mixed
     */
    public function getPotatoSendFirstName()
    {
        return $this->potatoSendFirstName;
    }

    /**
     * @return mixed
     */
    public function getPotatoSendLastName()
    {
        return $this->potatoSendLastName;
    }

    /**
     * @return string
     */
    public function getCallCallbackDataPre()
    {
        return $this->callCallbackDataPre;
    }

    /**
     * @return mixed
     */
    public function getWhiteCallbackDataPre()
    {
        return $this->whiteCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getUnwhiteCallbackDataPre()
    {
        return $this->unwhiteCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getWhitelistSwitchCallbackDataPre()
    {
        return $this->whitelistSwitchCallbackDataPre;
    }

    public function getUnwhitelistSwitchCallbackDataPre()
    {
        return $this->unwhitelistSwitchCallbackDataPre;
    }

    public function getBlackCallbackDataPre()
    {
        return $this->blackCallbackDataPre;
    }

    public function getUnblackCallbackDataPre()
    {
        return $this->unblackCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->llanguage;
    }

    /**
     * 获取语音重复次数.
     *
     * @return int
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * 获取声音性别.
     *
     * @return string
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * @return bool.
     */
    public function getIsUrgentCall()
    {
        return $this->isUrgentCall;
    }

    /**
     * @return string
     */
    public function getFirstText()
    {
        return $this->firstText;
    }

    /**
     * 获取code.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getBindCode()
    {
        return $this->bindCode;
    }

    /**
     * @return mixed
     */
    public function getKeyboard()
    {
        return $this->keyboard;
    }

    /**
     * @return string
     */
    public function getKeyboardText()
    {
        return Yii::t('app/model/potato', $this->keyboardText, array(), $this->language);
    }

    /**
     * @return mixed
     */
    public function getSendData()
    {
        return $this->sendData;
    }

    /**
     * 获取被叫用户数据.
     */
    public function getCalledPersonData()
    {
        return $this->calledPersonData;
    }

    /**
     * 获取主叫用户数据.
     */
    public function getCallPersonData()
    {
        return $this->callPersonData;
    }

    /**
     * 获取错误码.
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 获取错误消息.
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getStartText()
    {
        return Yii::t('app/model/potato', $this->startText, array(), $this->language);
    }

    /**
     * 欢迎.
     */
    public function getWellcomeText()
    {
        return Yii::t('app/model/potato', $this->wellcomeText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getBindRecommendText()
    {
        return Yii::t('app/model/potato', $this->bindRecommendText, array(), $this->language);
    }

    public function getBindVerifyCode()
    {
        return Yii::t('app/model/potato', $this->bindVerifyCode, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getMenuShareText()
    {
        return Yii::t('app/model/potato', $this->menuShareText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getSuccessText()
    {
        return Yii::t('app/model/potato', $this->successText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getFailureText()
    {
        return Yii::t('app/model/potato', $this->failureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCompleteText()
    {
        return Yii::t('app/model/potato', $this->completeText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getIsNotMemberText()
    {
        return Yii::t('app/model/potato', $this->isNotMemberText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getExceedText()
    {
        return Yii::t('app/model/potato', $this->exceedText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCodeEmptyText()
    {
        return Yii::t('app/model/potato', $this->codeEmptyText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCodeErrorText()
    {
        return Yii::t('app/model/potato', $this->codeErrorText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getWhiteSwitchText()
    {
        return Yii::t('app/model/potato', $this->whiteSwitchText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnwhiteSwitchText()
    {
        return Yii::t('app/model/potato', $this->unwhiteSwitchText, array(), $this->language);
    }

    /**
     * @return mixeds
     */
    public function getPotatoText()
    {
        return Yii::t('app/model/potato', $this->potatoText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getMenuNoMemberText()
    {
        return Yii::t('app/model/potato', $this->menuNoMemberText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getWhiteText()
    {
        return Yii::t('app/model/potato', $this->whiteText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnwhiteText()
    {
        return Yii::t('app/model/potato', $this->unwhiteText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getBlackText()
    {
        return Yii::t('app/model/potato', $this->blackText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnblackText()
    {
        return Yii::t('app/model/potato', $this->unblackText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableNoMemberText()
    {
        return Yii::t('app/model/potato', $this->enableNoMemberText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getJoinAlreadyText()
    {
        return Yii::t('app/model/potato', $this->joinAlreadyText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinWhiteListSuccess()
    {
        return Yii::t('app/model/potato', $this->joinWhiteListSuccess, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinWhiteListFailure()
    {
        return Yii::t('app/model/potato', $this->joinWhiteListFailure, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinRecommendText()
    {
        return Yii::t('app/model/potato', $this->joinRecommendText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnbindSuccessText()
    {
        return Yii::t('app/model/potato', $this->unbindSuccessText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnbindFailureText()
    {
        return Yii::t('app/model/potato', $this->unbindFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnbindNotText()
    {
        return Yii::t('app/model/potato', $this->unbindNotText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableWhiteText()
    {
        return Yii::t('app/model/potato', $this->enableWhiteText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableWhiteSuccessText()
    {
        return Yii::t('app/model/potato', $this->enableWhiteSuccessText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableWhiteFailureText()
    {
        return Yii::t('app/model/potato', $this->enableWhiteFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getDisableWhiteText()
    {
        return Yii::t('app/model/potato', $this->disableWhiteText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getDisableWhiteSuccessText()
    {
        return Yii::t('app/model/potato', $this->disableWhiteSuccessText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getDisableWhiteFailureText()
    {
        return Yii::t('app/model/potato', $this->disableWhiteFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinBlackListAreadyText()
    {
        return Yii::t('app/model/potato', $this->joinBlackListAreadyText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinBlackListSuccessText()
    {
        return Yii::t('app/model/potato', $this->joinBlackListSuccessText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinBlackListFailureText()
    {
        return Yii::t('app/model/potato', $this->joinBlackListFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnlockBlackListSuccessText()
    {
        return Yii::t('app/model/potato', $this->unlockBlackListSuccessText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getUnlockBlackListFailureText()
    {
        return Yii::t('app/model/potato', $this->unlockBlackListFailureText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getNotInBlackList()
    {
        return Yii::t('app/model/potato', $this->notInBlackList, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getRateText()
    {
        return Yii::t('app/model/potato', $this->rateText, array(), $this->language);
    }

    /**
     * 设置keyboard.
     */
    public function setKeyboard()
    {
        $this->keyboard = [
            [
                [
                    "type" => 0,
                    "text"=> $this->getKeyboardText(),
                    "request_contact"=> true,
                ]
            ]
        ];
    }

    /**
     * 检查频率.
     */
    public function checkRate()
    {
        $data = true;
        $cacheKey = $this->rateKey.$this->potatoUid;
        if (Yii::$app->redis->exists($cacheKey)) {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getRateText(),
            ];

            $this->sendPotatoData();
        } else {
            Yii::$app->redis->set($cacheKey, 1);
            Yii::$app->redis->expire($cacheKey, $this->rateExpireTime);
            $data = false;
        }

        return $data;
    }

    /**
     * 欢迎.
     */
    public function potatoWellcome()
    {
        $this->setKeyboard();
        // 发送操作菜单.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getWellcomeText(),
            'reply_keyboard' => [
                'resize_keyboard' => 1,
                'keyboard' => $this->getKeyboard(),
            ]
        ];

        $this->sendPotatoData();
        return $this->errorCode['success'];
    }

    /**
     * 发送绑定potato账号的验证码.
     */
    public function sendBindCode()
    {
        // 查询是否绑定自己的账号.
        if ($this->potatoUid != $this->potatoContactUid) {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getMenuShareText(),
            ];
        } else {
            $this->setCode();
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->code,
            ];
        }

        $this->sendPotatoData();
        return $this->errorCode['success'];
    }

    /**
     * 加入白名单.
     */
    public function joinWhiteList()
    {
        $sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => '',
        ];

        $time = time();
        //$this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->callPersonData = AppService::getUserByApp( $this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();

       // $this->calledPersonData = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        $this->calledPersonData = AppService::getUserByApp( $this->potatoContactUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
        if ($whiteRes) {
            $sendData['text'] = $this->getJoinAlreadyText();
        } else {
            $whiteRes = new WhiteList();
            $whiteRes->uid = $this->callPersonData->id;
            $whiteRes->white_uid = $this->calledPersonData->id;
            $res = $whiteRes->save();
            $res ? ($sendData['text'] = $this->getJoinWhiteListSuccess()) : ($sendData['text'] = $this->getJoinWhiteListFailure());
            $this->sendData = $sendData;
            $this->sendPotatoData();

            $res = WhiteList::findOne(['uid' => $this->calledPersonData->id, 'white_uid'=> $this->callPersonData->id]);
            if (empty($res)) {
                $this->language = $this->calledPersonData->language;
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoContactUid,
                    'text' => $this->potatoSendLastName . $this->potatoSendFirstName.$this->getJoinRecommendText(),
                ];
                $this->sendPotatoData();
                $bindMenu = [
                    'type' => 0,
                    'text' => $this->getWhiteText(),
                    'data' => implode('-', array($this->whiteCallbackDataPre, $this->potatoUid, $this->callPersonData->potato_number, $time)),
                ];
                $inlineKeyboard = [
                    [
                        $bindMenu
                    ]
                ];

                $this->webhook = $this->menuWebHookUrl;
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoContactUid,
                    'text' => $this->getPotatoText(),
                    'inline_markup' => $inlineKeyboard,

                ];
                $this->sendPotatoData();
            }
        }

        return $this->errorCode['success'];
    }

    /**
     * 解除白名单.
     */
    public function unbindWhiteList()
    {
        $sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => '',
        ];

        //$this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->callPersonData = AppService::getUserByApp($this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();
        //$this->calledPersonData = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        $this->calledPersonData = AppService::getUserByApp($this->potatoContactUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid' => $this->calledPersonData->id]);
        if ($whiteRes) {
            $res = $whiteRes->delete();
            $res ? ($sendData['text'] = $this->getUnbindSuccessText()) : ($sendData['text'] = $this->getUnbindFailureText());
        } else {
            $sendData['text'] = $this->getUnbindNotText();
        }


        $this->sendData = $sendData;
        return $this->sendPotatoData();
    }

    /**
     * 开启白名单功能.
     */
    public function enableWhiteSwith()
    {
        $sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => '',
        ];

        //$this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->callPersonData = AppService::getUserByApp($this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }


        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();
        if ($this->callPersonData->whitelist_switch == 1) {
            $sendData['text'] = $this->getEnableWhiteText();
        } else {
            $this->callPersonData->whitelist_switch=1;
            $res = $this->callPersonData->save();
            $res ? ($sendData['text'] = $this->getEnableWhiteSuccessText()) : ($sendData['text'] = $this->getEnableWhiteSuccessText());
        }

        $this->sendData = $sendData;
        return $this->sendPotatoData();
    }

    /**
     * 关闭白名单功能.
     */
    public function disableWhiteSwith()
    {
        $sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => '',
        ];

       // $this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->callPersonData = AppService::getUserByApp($this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }


        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();
        if ($this->callPersonData->whitelist_switch == 0) {
            $sendData['text'] = $this->getDisableWhiteText();
        } else {
            $this->callPersonData->whitelist_switch=0;
            $res = $this->callPersonData->save();
            $res ? ($sendData['text'] = $this->getDisableWhiteSuccessText()) : ($sendData['text'] = $this->getDisableWhiteFailureText());
        }

        $this->sendData = $sendData;
        return $this->sendPotatoData();
    }

    /**
     * 加入黑名单.
     */
    public function joinBlackList()
    {
        $sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => '',
        ];

        //$this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->callPersonData = AppService::getUserByApp($this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();
        //$this->calledPersonData = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        $this->calledPersonData   = AppService::getUserByApp($this->potatoContactUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid'=> $this->calledPersonData->id]);
        if ($blackRes) {
            $sendData['text'] = $this->getJoinBlackListAreadyText();
        } else {
            $blackRes = new BlackList();
            $blackRes->uid = $this->callPersonData->id;
            $blackRes->black_uid = $this->calledPersonData->id;
            $res = $blackRes->save();
            $res ? ($sendData['text'] = $this->getJoinBlackListSuccessText()) : ($sendData['text'] = $this->getJoinBlackListFailureText());
        }


        $this->sendData = $sendData;
        return $this->sendPotatoData();
    }

    /**
     * 解除白名单.
     */
    public function unbindBlackList()
    {
        $sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => '',
        ];

        //$this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->callPersonData = AppService::getUserByApp($this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();
        //$this->calledPersonData = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        $this->calledPersonData = AppService::getUserByApp($this->potatoContactUid , UserBindApp::APP_TYPE_POTATO);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendPotatoData();
        }

        $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid' => $this->calledPersonData->id]);
        if ($blackRes) {
            $res = $blackRes->delete();
            $res ? ($sendData['text'] = $this->getUnlockBlackListSuccessText()) : ($sendData['text'] = $this->getUnlockBlackListFailureText());
        } else {
            $sendData['text'] = $this->getNotInBlackList();
        }


        $this->sendData = $sendData;
        return $this->sendPotatoData();
    }

    /**
     * 发送菜单.
     */
    public function sendMenulist()
    {
       // $this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
       // $this->calledPersonData = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        $this->callPersonData  = AppService::getUserByApp($this->potatoUid , UserBindApp::APP_TYPE_POTATO);
        $this->calledPersonData= AppService::getUserByApp($this->potatoContactUid , UserBindApp::APP_TYPE_POTATO);
        $time = time();
        // 先检查自己是否完成绑定操作.
        if (empty($this->callPersonData) && ($this->potatoUid == $this->potatoContactUid)) {
            // 发送验证码完成绑定.
            return $this->sendBindCode();
        } elseif (empty($this->callPersonData) && ($this->potatoUid != $this->potatoContactUid)) {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getMenuShareText(),
            ];
            return $this->sendPotatoData();
        } elseif (!empty($this->callPersonData) && ($this->potatoUid == $this->potatoContactUid)){
            $this->language = $this->callPersonData->language;
            if ($this->callPersonData->whitelist_switch == 0) {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getWhiteSwitchText(),
                    'data' => implode('-', array($this->whitelistSwitchCallbackDataPre, $this->potatoUid, $this->potatoContactPhone, $time)),
                ];
            } else {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getUnwhiteSwitchText(),
                    'data' => implode('-', array($this->unwhitelistSwitchCallbackDataPre, $this->potatoUid, $this->potatoContactPhone, $time)),
                ];
            }

            $inlineKeyboard =[
                [
                    $whiteMenu
                ]
            ];
            $this->sendData = [
                "chat_type" => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getPotatoText(),
                'inline_markup' => $inlineKeyboard,
            ];
        } else {
            if (empty($this->calledPersonData)) {
                $sendData['chat_type'] = 1;
                $sendData['chat_id'] = $this->potatoUid;
                $sendData['text'] = $this->getMenuNoMemberText();
                $this->sendData = $sendData;
                return $this->sendPotatoData();
            }
            $this->language = $this->callPersonData->language;
            $callMenu = [
                'type' => 0,
                'text' => $this->getCallText(),
                'data' => implode('-', array($this->callCallbackDataPre, $this->potatoContactUid, $this->potatoSendLastName.$this->potatoSendFirstName, $this->potatoContactLastName.$this->potatoContactFirstName, $time)),
            ];

            // 检查是否加了呼叫人到自己到白名单.
            $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
            $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid'=> $this->calledPersonData->id]);
            if ($whiteRes) {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getUnwhiteText(),
                    'data' => implode('-', array($this->unwhiteCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone, $time)),
                ];
            } else {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getWhiteText(),
                    'data' => implode('-', array($this->whiteCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone, $time)),
                ];
            }
            // 黑名单按钮.
            if ($blackRes) {
                $blackMenu = [
                    'type' => 0,
                    'text' => $this->getUnblackText(),
                    'data' => implode('-', array($this->unblackCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone, $time)),
                ];
            } else {
                $blackMenu = [
                    'type' => 0,
                    'text' => $this->getBlackText(),
                    'data' => implode('-', array($this->blackCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone, $time)),
                ];
            }

            $inlineKeyboard =[
                [
                    $whiteMenu,
                    $blackMenu
                ],
                [
                    $callMenu
                ]
            ];

            $this->sendData = [
                "chat_type" => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getPotatoText(),
                'inline_markup' => $inlineKeyboard,
            ];
        }

        $this->webhook = $this->menuWebHookUrl;
        return $this->sendPotatoData();
    }

    /**
     * 呼叫potato账号.
     */
    public function callPotatoPerson($calledId = '')
    {
        $res = User::findOne(['potato_user_id' => $this->potatoUid]);
        if (!$res) {
            // 发送验证码，完成绑定.
            return $this->sendBindCode();
        } elseif ($this->potatoUid == $this->potatoContactUid) {
            $this->language = $res->language;
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getCompleteText(),
            ];
            $this->sendPotatoData();
            return $this->errorCode['success'];
        }

        $this->callPersonData = $res;
        $this->language = $this->callPersonData->language;
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->getStartText(),
        ];
        $this->sendPotatoData();
        $user = User::findOne(['potato_user_id' => $this->potatoContactUid]);
        if ($user) {
            $this->calledPersonData = $user;
            $nickname = $this->potatoContactFirstName;
            if (empty($nickname)) {
                $nickname = !empty($user->nickname) ? $user->nickname : '他/她';
            }

            // 黑名单检查.
            $res = $this->blackList();
            if ($res) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->translateLanguage('您在"'.$nickname.'"的黑名单列表内, 不能呼叫!'),
                ];
                $this->sendPotatoData();
                return $this->errorCode['success'];
            }

            // 白名单检查.
            if ($this->calledPersonData->whitelist_switch == 1) {
                $res = $this->whiteList();
                if (!$res) {
                    $this->sendData = [
                        'chat_type' => 1,
                        'chat_id' => $this->potatoUid,
                        'text' => $this->translateLanguage('您不在' . $nickname . '的白名单列表内, 不能呼叫!'),
                    ];
                    $this->sendPotatoData();
                    return $this->errorCode['success'];
                }
            }

            // 呼叫限制检查.
            $res = $this->callLimit();
            if (!$res['status']) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->translateLanguage('呼叫"'.$nickname.'"失败! '.$res['message']),
                ];
                $this->sendPotatoData();
                return $this->errorCode['success'];
            }

            // 呼叫.
            try {
                if (!empty($calledId)) {
                    $urgent = UserGentContact::find()->select(['id', 'contact_country_code', 'contact_phone_number', 'contact_nickname'])->where(['user_id' => $calledId])->orderBy('id asc')->all();
                    $urgentArr = [];
                    if (!empty($urgent)) {
                        foreach ($urgent as $key => $value) {
                            $tmp = [];
                            $tmp['phone_number'] = $value->contact_country_code . $value->contact_phone_number;
                            $tmp['nickname'] = $value->contact_nickname;
                            $urgentArr[] = $tmp;
                        }
                    }
                    $nexmo = new Nexmo();
                    $nexmo->callPerson($this->calledPersonData->id, $this->callPersonData->id, $this->potatoContactFirstName, $this->potatoSendFirstName, $this->calledPersonData->nickname, $this->callPersonData->nickname, $this->callPersonData->country_code . $this->callPersonData->phone_number, $this->language, $appName = 'potato', $this->potatoUid, $this->potatoContactUid, 0, array(), $urgentArr, 1);
                } else {
                    $nexmo = new Nexmo();
                    $nexmo->callPerson($this->calledPersonData->id, $this->callPersonData->id, $this->potatoContactFirstName, $this->potatoSendFirstName, $this->calledPersonData->nickname, $this->callPersonData->nickname, $this->callPersonData->country_code . $this->callPersonData->phone_number, $this->language, $appName = 'potato', $this->potatoUid, $this->potatoContactUid,1);
                }
            } catch (\Exception $e) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->translateLanguage('网络异常, 请稍后再试!'),
                ];
                $this->sendPotatoData();
                $file = 'pnexmo_'.date('Y-m-d', time()).'.txt';
                file_put_contents('/tmp/'.$file, var_export($e->getMessage(), true).PHP_EOL, 8);
            }

            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->potatoContactFirstName.$this->getIsNotMemberText(),
            ];
            $this->sendPotatoData();
        }
    }

    /**
     * 呼叫限制.
     */
    public function callLimit()
    {
        $res = [
            'status' => true,
            'message' => '',
        ];
        // 有呼叫限制的.
        if ($this->calledPersonData->long_time && $this->calledPersonData->un_call_number) {
            $cacheKey = $this->calledPersonData->id;
            $callKey = $this->callPersonData->country_code.$this->callPersonData->phone_number;
            if (!Yii::$app->redis->exists($cacheKey)) {
                Yii::$app->redis->hset($cacheKey, 'total', 1);
                Yii::$app->redis->hset($cacheKey, $callKey, 1);
                Yii::$app->redis->expire($cacheKey, $this->calledPersonData->long_time * 60);
            } else {
                $totalNum = Yii::$app->redis->hget($cacheKey, 'total');
                $personNum = Yii::$app->redis->hexists($cacheKey, $callKey) ? Yii::$app->redis->hget($cacheKey, $callKey) : 0;
                if ($totalNum >= $this->calledPersonData->un_call_number || $personNum >= $this->calledPersonData->un_call_by_same_number) {
                    $res['status'] = false;
                    $res['message'] = $this->getExceedText();
                    return $res;
                }
                Yii::$app->redis->hincrby($cacheKey, 'total', 1);
                Yii::$app->redis->hexists($cacheKey, $callKey) ? Yii::$app->redis->hincrby($cacheKey, $callKey, 1) : Yii::$app->redis->hset($cacheKey, $callKey, 1);
            }
        }

        return $res;
    }

    /**
     * 白名单限制.
     */
    public function whiteList()
    {
        return WhiteList::findOne(['uid' => $this->calledPersonData->id, 'white_uid'=> $this->callPersonData->id]);
    }

    /**
     * 黑名单限制.
     */
    public function blackList()
    {
        return BlackList::findOne(['uid' => $this->calledPersonData->id, 'black_uid'=> $this->callPersonData->id]);
    }

    /**
     * 绑定操作.
     */
    public function bindPotatoData($app_id = 0)
    {
        $user = User::findOne(Yii::$app->user->id);
        $this->language = $user->language;
        if(empty($this->bindCode)){
            return  $this->addError('bindCode',$this->getCodeEmptyText());
        }
        if (!Yii::$app->redis->exists($this->bindCode)) {
            $this->addError('bindCode', $this->getCodeErrorText());
        } else {
            $potatoData = Yii::$app->redis->get($this->bindCode);
        }
        if (empty($potatoData)) {
            return $this->addError('bindCode', $this->getCodeErrorText());
        }

        $data = Yii::$app->security->decryptByKey(base64_decode($potatoData), Yii::$app->params['potato']);
        $dataArr = explode('-', $data);
        if ($dataArr[0] == Yii::$app->params['potato_pre']) {
            if(!$app_id){
                $app = new UserBindApp();
            }else{
                $app = UserBindApp::findOne($app_id);
                if(empty($app)){
                    return false;
                }
            }
            $app->type       =  UserBindApp::APP_TYPE_POTATO;
            $app->app_userid = $dataArr['1'];
            $app->app_number = $dataArr['2'];
            $app->app_name   = $dataArr['3'];
            $app->user_id    = Yii::$app->user->id;
//            $user->potato_user_id = $dataArr['1'];
//            $user->potato_number = $dataArr['2'];
//            $user->potato_name = $dataArr['3'];
            $res = $app->save();
            if ($res) {
                Yii::$app->redis->del($this->bindCode);
            }
            return $res;
        } else {
            return $this->addError('bindCode', $this->getCodeErrorText());
        }

    }

    /**
     * 解除绑定操作.
     */
    public function unbundlePotatoData($app_id = 0)
    {
        //$user = User::findOne(Yii::$app->user->id);
        if($app_id){
            $app = UserBindApp::findOne(['id'=>$app_id , 'user_id'=>Yii::$app->user->id]);
            if(!empty($app)){
                return  $app->delete();
            }
        }
        return false;

//        $user->potato_user_id = 0;
//        $user->potato_number = 0;
//        return $user->save();
    }

    /**
     * 翻译语言.
     */
    public function translateLanguage($text, $source = null)
    {

        $textArr = [
            "q" => $text,
            "format" => "text",
            "target" => $this->tlanguage,
        ];

        $data = $text;
        if (!empty($source)) {
            $textArr['source'] = $source;
        }
        $this->sendData = $textArr;
        $res = $this->sendPotatoData($this->translateUrl, true);
        $res = json_decode($res, true);

        if (isset($res['data']) && isset($res['data']['translations'])) {
            $data = $res['data']['translations'][0]['translatedText'];
        }

        return $data;
    }

    /**
     * 发送菜单.
     *
     * @return json.
     */
    public function sendPotatoData($url = null)
    {
        if (empty($this->potatoUid)) {
            return "error #:".$this->errorCode['emptyuid'];
        }
        if (is_array($this->sendData)) {
            $this->sendData = json_encode($this->sendData, true);
        }
        if (empty($url)) {
            $url = $this->getWebhook();
        }
        file_put_contents('/tmp/cc_potato'.date('Y-m-d').'.log' , var_export($this->sendData , true) , 8);
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
                CURLOPT_POSTFIELDS => $this->sendData,
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