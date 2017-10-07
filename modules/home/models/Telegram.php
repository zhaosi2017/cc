<?php
namespace app\modules\home\models;

use app\modules\home\servers\appService\TraitTelegram;
use app\modules\home\servers\TTSservice\TTSservice;
use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;
use app\modules\home\models\WhiteList;
use app\modules\home\models\BlackList;
use app\modules\home\models\UserPhone;
use app\modules\home\models\Nexmo;

class Telegram extends Model
{
    use TraitTelegram;
    const CODE_LENGTH = 5;

    private $telegramText = 'Operation menu.';
    private $startText = 'Start the operation, please wait later.';
    private $wellcomeText = 'welcome!';
    private $keyboardText = 'Share your contact card';
    private $callText = "call";
    private $firstText = '/start';
    private $webhook;
    private $nexmoUrl = 'https://api.nexmo.com/v1/calls';
    private $translateUrl = "https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk";
    private $apiKey = '85704df7';
    private $apiSecret = '755026fdd40f34c2';
    private $llanguage = 'zh-CN';
    private $tlanguage = 'zh-CN';
    private $repeat = 3;
    private $voice = 'male';
    private $rateKey = 'rateKey_telegram_';
    private $rateExpireTime = 10;
    private $rateText = 'Operation too fast, please try again later!';
    // 是否是紧急呼叫.
    private $successText = 'success';
    private $failureText = 'failure';
    private $isUrgentCall = 0;
    private $callCallbackDataPre = 'cc_call';
    private $callUrgentCallbackDataPre = 'cc_call_urgent';
    private $whiteCallbackDataPre = 'cc_white';
    private $unwhiteCallbackDataPre = 'cc_unwhite';
    private $whitelistSwitchCallbackDataPre = 'cc_whiteswitch';
    private $unwhitelistSwitchCallbackDataPre = 'cc_unwhiteswitch';
    private $blackCallbackDataPre = "cc_black";
    private $unblackCallbackDataPre = "cc_unblack";
    private $whiteText = 'Join Whitelist';
    private $unwhiteText = 'Unlock the whitelist';
    private $blackText = "Join blacklist";
    private $unblackText = "Unlock the blacklist";
    private $whiteSwitchText = 'Open the whitelist';
    private $unwhiteSwitchText = 'Close the whitelist';
    private $menuShareText = "Please share your own contact card to the robot, complete the binding operation.";
    private $menuNoMemberText = "He is not a member of our system and you can not perform this operation.";
    private $enableNoMemberText = "You are not a member of our system and can not perform this operation.";
    private $enableWhiteText = "White List has been turned on.";
    private $enableWhiteSuccessText = "Open white list function successfully.";
    private $enableWhiteFailureText = "Open whitelist failed.";
    private $disableWhiteText = "Has closed the whitelist function.";
    private $disableWhiteSuccessText = "Close White List Function successfully.";
    private $disableWhiteFailureText = "Close whitelist failed.";
    private $joinAlreadyText = "Already in the white list.";
    private $joinWhiteListSuccess = "Join whitelist successfully.";
    private $joinWhiteListFailure = "Join whitelist failed.";
    private $joinRecommendText = "Has already added you to the whitelist, you can also click the button below to add him to your whitelist.";
    private $unbindSuccessText = "Cancel the whitelist successfully.";
    private $unbindFailureText = "Cancel the whitelist failed.";
    private $unbindNotText = "Not in the white list.";
    private $joinBlackListAreadyText = "Already in the blacklist.";
    private $joinBlackListSuccessText = "Add to Blacklist successfully.";
    private $joinBlackListFailureText = "Add to Blacklist failed.";
    private $unlockBlackListSuccessText = "Unlock the blacklist successfully.";
    private $unlockBlackListFailureText = "Unlock the blacklist failed.";
    private $notInBlackList = "Not in blacklist.";
    private $isNotMemberText = 'is not a member of our system, can not perform the operation';
    private $completeText = 'You have completed the binding operation.';
    private $exceedText = 'The number of times the call has exceeded the limit set by he.';
    private $codeEmptyText = 'The verification code is empty.';
    private $codeErrorText = 'Verification code error.';
    private $bindRecommendText = "[<a href='https://www.callu.online/home/telegram/bind-telegram'>Please enter the verification code on the callu platform to complete the binding operation!</a>]";


    private $keyboard;
    private $code;
    private $bindCode;
    private $telegramUid;
    private $telegramFirstName = '';
    private $telegramLastName = '';
    private $telegramContactUid;
    private $telegramContactPhone = '';
    private $telegramContactFirstName = '';
    private $telegramContactLastName = '';
    private $callbackQuery;
    private $callPersonData;
    private $calledPersonData;

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
    public function setWebhook()
    {
//        $this->webhook = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
        $this->webhook = 'https://api.telegram.org/bot445351636:AAG4wnw7jI048KBKlb0P0BwoU08Dm6811j8/sendMessage';
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
    public function setTelegramUid($value)
    {
        $this->telegramUid = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramFirstName($value)
    {
        $this->telegramFirstName = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramLastName($value)
    {
        $this->telegramLastName = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramContactUid($value)
    {
        $this->telegramContactUid = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramContactPhone($value)
    {
        $this->telegramContactPhone = trim($value, '+');
    }

    /**
     * 设置名.
     *
     * @param string $value 名.
     */
    public function setTelegramContactFirstName($value)
    {
        $this->telegramContactFirstName = $value;
    }

    /**
     * 设置姓.
     *
     * @param string $value 名.
     */
    public function setTelegramContactLastName($value)
    {
        $this->telegramContactLastName = $value;
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
        // 查询是否绑定.
        $dealData = [
            Yii::$app->params['telegram_pre'],
            $this->telegramContactUid,
            $this->telegramContactPhone,
            $this->telegramLastName.$this->telegramFirstName,
        ];

        $dealData = implode('-', $dealData);
        $this->code = $this->makeCode();
        $telegramData = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['telegram']));
        // 验证码过期时间半小时.
        Yii::$app->redis->setex($this->code, 30*60, $telegramData);
        $this->code = $this->code.' '.$this->getBindRecommendText();
    }

    /**
     * @param $value
     */
    public function setBindCode($value)
    {
        $this->bindCode = strtolower(trim($value));
    }

    /**
     * 设置回调数据.
     */
    public function setCallbackQuery($value)
    {
        $this->callbackQuery = $value;
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
     * @return mixeds
     */
    public function getTelegramText()
    {
        return Yii::t('app/model/telegram', $this->telegramText, array(), $this->language);
    }

    /**
     * @return mixed
     */
    public function getTelegramUid()
    {
        return $this->telegramUid;
    }

    /*
     * @return string
     */
    public function getTelegramFirstName()
    {
        return $this->telegramFirstName;
    }

    /**
     * @return mixedss
     */
    public function getTelegramLastName()
    {
        return $this->telegramLastName;
    }

    /**
     * @return mixed
     */
    public function getTelegramContactFirstName()
    {
        return $this->telegramContactFirstName;
    }

    /**
     * @return mixed
     */
    public function getTelegramContactLastName()
    {
        return $this->telegramContactLastName;
    }

    /**
     * 获取webhook.
     */
    public function getWebhook()
    {
        return $this->webhook;
    }

    /**
     * @return nexmo.
     */
    public function getNexmoUrl()
    {
        return $this->nexmoUrl;
    }

    /**
     * 获取apikey.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * 获取apiSecret.
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
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

    public function getCallUrgentCallbackDataPre()
    {
        return $this->callUrgentCallbackDataPre;
    }

    public function getWhiteText()
    {
        return Yii::t('app/model/telegram', $this->whiteText, array(), $this->language);
    }

    public function getUnwhiteText()
    {
        return Yii::t('app/model/telegram', $this->unwhiteText, array(), $this->language);
    }

    public function getBlackText()
    {
        return Yii::t('app/model/telegram', $this->blackText, array(), $this->language);
    }

    public function getUnblackText()
    {
        return Yii::t('app/model/telegram', $this->unblackText, array(), $this->language);
    }

    public function getWhiteSwitchText()
    {
        return Yii::t('app/model/telegram', $this->whiteSwitchText, array(), $this->language);
    }

    public function getUnwhiteSwitchText()
    {
        return Yii::t('app/model/telegram', $this->unwhiteSwitchText, array(), $this->language);
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
        // return Yii::t('app/model/telegram', $this->firstText, array(), $this->language);
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
        return Yii::t('app/model/telegram', $this->keyboardText, array(), $this->language);
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
        return Yii::t('app/model/telegram', $this->startText, array(), $this->language);
    }

    /**
     * 欢迎.
     */
    public function getWellcomeText()
    {
        return Yii::t('app/model/telegram', $this->wellcomeText, array(), $this->language);
    }

    /**
     * 返回回调数据.
     */
    public function getCallbackQuery()
    {
        return $this->callbackQuery;
    }

    /**
     * @return string
     */
    public function getMenuShareText()
    {
        return Yii::t('app/model/telegram', $this->menuShareText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getMenuNoMemberText()
    {
        return Yii::t('app/model/telegram', $this->menuNoMemberText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableNoMemberText()
    {
        return Yii::t('app/model/telegram', $this->enableNoMemberText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableWhiteText()
    {
        return Yii::t('app/model/telegram', $this->enableWhiteText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableWhiteSuccessText()
    {
        return Yii::t('app/model/telegram', $this->enableWhiteSuccessText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getEnableWhiteFailureText()
    {
        return Yii::t('app/model/telegram', $this->enableWhiteFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getDisableWhiteText()
    {
        return Yii::t('app/model/telegram', $this->disableWhiteText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getDisableWhiteSuccessText()
    {
        return Yii::t('app/model/telegram', $this->disableWhiteSuccessText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getDisableWhiteFailureText()
    {
        return Yii::t('app/model/telegram', $this->disableWhiteFailureText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getJoinAlreadyText()
    {
        return Yii::t('app/model/telegram', $this->joinAlreadyText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinWhiteListSuccess()
    {
        return Yii::t('app/model/telegram', $this->joinWhiteListSuccess, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinWhiteListFailure()
    {
        return Yii::t('app/model/telegram', $this->joinWhiteListFailure, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinRecommendText()
    {
        return Yii::t('app/model/telegram', $this->joinRecommendText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnbindSuccessText()
    {
        return Yii::t('app/model/telegram', $this->unbindSuccessText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnbindFailureText()
    {
        return Yii::t('app/model/telegram', $this->unbindFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnbindNotText()
    {
        return Yii::t('app/model/telegram', $this->unbindNotText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinBlackListAreadyText()
    {
        return Yii::t('app/model/telegram', $this->joinBlackListAreadyText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinBlackListSuccessText()
    {
        return Yii::t('app/model/telegram', $this->joinBlackListSuccessText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getJoinBlackListFailureText()
    {
        return Yii::t('app/model/telegram', $this->joinBlackListFailureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getUnlockBlackListSuccessText()
    {
        return Yii::t('app/model/telegram', $this->unlockBlackListSuccessText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getUnlockBlackListFailureText()
    {
        return Yii::t('app/model/telegram', $this->unlockBlackListFailureText, array(), $this->language);
    }

    /**
     * @return string.
     */
    public function getNotInBlackList()
    {
        return Yii::t('app/model/telegram', $this->notInBlackList, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getSuccessText()
    {
        return Yii::t('app/model/telegram', $this->successText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getFailureText()
    {
        return Yii::t('app/model/telegram', $this->failureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCallText()
    {
        return Yii::t('app/model/telegram', $this->callText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getIsNotMemberText()
    {
        return Yii::t('app/model/telegram', $this->isNotMemberText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCompleteText()
    {
        return Yii::t('app/model/telegram', $this->completeText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getExceedText()
    {
        return Yii::t('app/model/telegram', $this->exceedText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCodeEmptyText()
    {
        return Yii::t('app/model/telegram', $this->codeEmptyText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCodeErrorText()
    {
        return Yii::t('app/model/telegram', $this->codeErrorText, array(), $this->language);
    }

    /*
     * @return string.
     */
    public function getBindRecommendText()
    {
        return Yii::t('app/model/telegram', $this->bindRecommendText, array(), $this->language);
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
                    "text"=> $this->keyboardText,
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
        $cacheKey = $this->rateKey.$this->telegramUid;
        if (Yii::$app->redis->exists($cacheKey)) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->getRateText(),
            ];

            $this->sendTelegramData();
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
    public function telegramWellcome()
    {
        $this->setKeyboard();
        $keyboard = [
            [
                [
                    "text"=> $this->getKeyboardText(),
                    "request_contact"=> true,
                ]
            ]
        ];
        // 发送操作菜单.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'reply_to_message_id' => 0,
            'text' => $this->getWellcomeText(),
            'reply_markup' => [
                'keyboard' => $keyboard,
            ]
        ];

        $this->sendTelegramData();
        return $this->errorCode['success'];
    }

    /**
     * 发送菜单.
     */
    public function sendMenulist()
    {
        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        // 先检查自己是否完成绑定操作.
        if (empty($this->callPersonData) && ($this->telegramUid == $this->telegramContactUid)) {
            // 发送验证码完成绑定.
            $this->setCode();
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->code,
                "parse_mode"=> "HTML",
            ];
        } elseif (empty($this->callPersonData) && ($this->telegramUid != $this->telegramContactUid)) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->getMenuShareText(),
            ];
        } elseif (!empty($this->callPersonData) && ($this->telegramUid == $this->telegramContactUid)){
            $this->language = $this->callPersonData->language;
            if ($this->callPersonData->whitelist_switch == 0) {
                $whiteMenu = [
                    'text' => $this->getWhiteSwitchText(),
                    'callback_data' => implode('-', array($this->whitelistSwitchCallbackDataPre, $this->telegramUid, $this->callPersonData->telegram_number)),
                ];
            } else {
                $whiteMenu = [
                    'text' => $this->getUnwhiteSwitchText(),
                    'callback_data' => implode('-', array($this->unwhitelistSwitchCallbackDataPre, $this->telegramUid, $this->callPersonData->telegram_number)),
                ];
            }

            $inlineKeyboard =[
                [
                    $whiteMenu
                ]
            ];
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->getTelegramText(),
                'reply_markup' => [
                    'inline_keyboard' => $inlineKeyboard,
                ]
            ];
        } else {
            if (empty($this->calledPersonData)) {
                $sendData['chat_id'] = $this->telegramUid;
                $sendData['text'] = $this->getMenuNoMemberText();
                $this->sendData = $sendData;
                return $this->sendTelegramData();
            }
            $this->language = $this->callPersonData->language;
            $callMenu = [
                'text' => $this->getCallText(),
                'callback_data' => implode('-', array($this->callCallbackDataPre, $this->telegramContactUid, $this->telegramFirstName.$this->telegramLastName, $this->telegramContactLastName.$this->telegramContactFirstName)),
            ];

            // 检查是否加了呼叫人到自己到白名单.
            $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
            $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid'=> $this->calledPersonData->id]);
            if ($whiteRes) {
                $whiteMenu = [
                    'text' => $this->getUnwhiteText(),
                    'callback_data' => implode('-', array($this->unwhiteCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
                ];
            } else {
                $whiteMenu = [
                    'text' => $this->getWhiteText(),
                    'callback_data' => implode('-', array($this->whiteCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
                ];
            }
            // 黑名单按钮.
            if ($blackRes) {
                $blackMenu = [
                    'text' => $this->getUnblackText(),
                    'callback_data' => implode('-', array($this->unblackCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
                ];
            } else {
                $blackMenu = [
                    'text' => $this->getBlackText(),
                    'callback_data' => implode('-', array($this->blackCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
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
                'chat_id' => $this->telegramUid,
                'text' => $this->getTelegramText(),
                'reply_markup' => [
                    'inline_keyboard' => $inlineKeyboard,
                ]
            ];
        }

        return $this->sendTelegramData();
    }

    /**
     * 开启白名单功能.
     */
    public function enableWhiteSwith()
    {
        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }


        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();
        if ($this->callPersonData->whitelist_switch == 1) {
            $sendData['text'] = $this->getEnableWhiteText();
        } else {
            $this->callPersonData->whitelist_switch=1;
            $res = $this->callPersonData->save();
            $res ? ($sendData['text'] = $this->getEnableWhiteSuccessText()) : ($sendData['text'] = $this->getEnableWhiteSuccessText());
        }

        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 关闭白名单功能.
     */
    public function disableWhiteSwith()
    {
        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }


        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();
        if ($this->callPersonData->whitelist_switch == 0) {
            $sendData['text'] = $this->getDisableWhiteText();
        } else {
            $this->callPersonData->whitelist_switch=0;
            $res = $this->callPersonData->save();
            $res ? ($sendData['text'] = $this->getDisableWhiteSuccessText()) : ($sendData['text'] = $this->getDisableWhiteFailureText());
        }

        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 加入白名单.
     */
    public function joinWhiteList()
    {
        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();

        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
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

            $res = WhiteList::findOne(['uid' => $this->calledPersonData->id, 'white_uid'=> $this->callPersonData->id]);
            if (empty($res)) {
                $this->language = $this->calledPersonData->language;
                $this->sendData = [
                    'chat_id' => $this->telegramContactUid,
                    'text' => $this->telegramLastName . $this->telegramFirstName.$this->getJoinRecommendText(),
                ];
                $this->sendTelegramData();
                $bindMenu = [
                    'text' => $this->getWhiteText(),
                    'callback_data' => implode('-', array($this->whiteCallbackDataPre, $this->telegramUid, $this->callPersonData->telegram_number)),
                ];
                $inlineKeyboard = [
                    [
                        $bindMenu
                    ]
                ];
                $this->sendData = [
                    'chat_id' => $this->telegramContactUid,
                    'text' => $this->getTelegramText(),
                    'reply_markup' => [
                        'inline_keyboard' => $inlineKeyboard,
                    ]
                ];
                $this->sendTelegramData();
            }
        }

        $this->language = $this->callPersonData->language;
        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 解除白名单.
     */
    public function unbindWhiteList()
    {
        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();
        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid' => $this->calledPersonData->id]);
        if ($whiteRes) {
            $res = $whiteRes->delete();
            $res ? ($sendData['text'] = $this->getUnbindSuccessText()) : ($sendData['text'] = $this->getUnbindFailureText());
        } else {
            $sendData['text'] = $this->getUnbindNotText();
        }


        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 加入黑名单.
     */
    public function joinBlackList()
    {
        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();
        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
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
        return $this->sendTelegramData();
    }

    /**
     * 解除白名单.
     */
    public function unbindBlackList()
    {
        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = $this->getEnableNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->language = $this->callPersonData->language;
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();
        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = $this->getMenuNoMemberText();
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid' => $this->calledPersonData->id]);
        if ($blackRes) {
            $res = $blackRes->delete();
            $res ? ($sendData['text'] = $this->getUnlockBlackListSuccessText()) : ($sendData['text'] = $this->getUnlockBlackListFailureText());
        } else {
            $sendData['text'] = $this->getNotInBlackList();
        }


        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 发送绑定telegram账号的验证码.
     */
    public function sendBindCode()
    {
        // 查询是否绑定自己的账号.
        if ($this->telegramUid != $this->telegramContactUid) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->getMenuShareText(),
            ];
        } else {
            $this->setCode();
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->code,
                "parse_mode"=> "HTML",
            ];
        }

        $this->sendTelegramData();
        return $this->errorCode['success'];
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
        $res = $this->sendTelegramData($this->translateUrl, true);
        $res = json_decode($res, true);

        if (isset($res['data']) && isset($res['data']['translations'])) {
            $data = $res['data']['translations'][0]['translatedText'];
        }

        return $data;
    }

    /**
     * 呼叫telegram账号.
     */
    public function callTelegramPerson($calledId='')
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
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->getStartText(),
        ];
        $this->sendTelegramData();
        $user = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if ($user) {
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
                    $nexmo->callPerson($this->calledPersonData->id, $this->callPersonData->id, $this->telegramContactFirstName, $this->telegramFirstName, $this->calledPersonData->nickname, $this->callPersonData->nickname, $this->callPersonData->country_code . $this->callPersonData->phone_number, $this->language, $appName = 'potato', $this->potatoUid, $this->potatoContactUid, 0, array(), $urgentArr, 1);
                } else {
                    $nexmo = new Nexmo();
                    $nexmo->callPerson($this->calledPersonData->id, $this->callPersonData->id, $this->telegramContactFirstName, $this->telegramFirstName, $this->calledPersonData->nickname, $this->callPersonData->nickname, $this->callPersonData->country_code . $this->callPersonData->phone_number, $this->language, $appName = 'potato', $this->potatoUid, $this->potatoContactUid,1);
                }
            } catch (\Exception $e) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->telegramUid,
                    'text' => $this->translateLanguage('网络异常, 请稍后再试!'),
                ];
                $this->sendPotatoData();
            }
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
     * @param string $nickname 呼叫人.
     * @param arra   $data     数据.
     *
     * @return boolean
     */
    public function callPerson($data)
    {
        $result = [
            'status' => true,
            'message' => '',
        ];

        $this->sendData = $data;
        $res = $this->sendTelegramData($this->nexmoUrl);
        $res = json_decode($res, true);
        if ($res['status'] != 0) {
            $result['status'] = false;
        }

        return $result;
    }

    /**
     * 绑定操作.
     */
    public function bindTelegramData()
    {
        $user = User::findOne(Yii::$app->user->id);
        $this->language = $user->language;
        
        if(empty($this->bindCode)){
            return  $this->addError('bindCode', $this->getCodeEmptyText());
        }
        if (!Yii::$app->redis->exists($this->bindCode)) {
            $this->addError('bindCode', $this->getCodeErrorText());
        } else {
            $telegramData = Yii::$app->redis->get($this->bindCode);
        }
        if (empty($telegramData)) {
            return $this->addError('bindCode', $this->getCodeErrorText());
        }

        $data = Yii::$app->security->decryptByKey(base64_decode($telegramData), Yii::$app->params['telegram']);
        $dataArr = explode('-', $data);
        if ($dataArr[0] == Yii::$app->params['telegram_pre']) {
            $user->telegram_user_id = $dataArr['1'];
            $user->telegram_number = $dataArr['2'];
            $user->telegram_name = $dataArr['3'];
            $res = $user->save();
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
    public function unbundleTelegramData()
    {
        $user = User::findOne(Yii::$app->user->id);
        $user->telegram_user_id = 0;
        $user->telegram_number = 0;
        return $user->save();
    }

    /**
     * 发送菜单.
     *
     * @return json.
     */
    public function sendTelegramData($url = null, $escape = false)
    {
        if (empty($this->telegramUid)) {
            return "error #:".$this->errorCode['emptyuid'];
        }
        if (is_array($this->sendData)) {
            $this->sendData = $escape ? json_encode($this->sendData,JSON_UNESCAPED_UNICODE) : json_encode($this->sendData, true);
            // $this->sendData = json_encode($this->sendData, true);
        }
        if (empty($url)) {
            $this->setWebhook();
            $url = $this->webhook;
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