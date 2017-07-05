<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;

class Potato extends Model
{

    const CODE_LENGTH = 5;

    private $potatoText = "Operation menu.";
    private $startText = 'Start the operation, please wait later.';
    private $wellcomeText = 'welcome';
    private $keyboardText = 'Share your contact card';
    private $firstText = '/start';
    private $webhook;
    private $nexmoUrl = "https://api.nexmo.com/tts/json";
    private $translateUrl = "https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk";
    private $apiKey = '85704df7';
    private $apiSecret = '755026fdd40f34c2';
    private $tlanguage = 'zh-CN';
    private $llanguage = 'zh-cn';
    private $repeat = 3;
    private $voice = 'male';
    // 是否是紧急呼叫.
    private $isUrgentCall = 0;

    private $code;
    private $bindCode;
    private $potatoUid;
    private $shareRequestType = 4;
    private $callBackRequestType = 2;
    private $callCallbackDataPre = 'cc_call';
    private $whiteCallbackDataPre = 'cc_white';
    private $unwhiteCallbackDataPre = 'cc_unwhite';
    private $whitelistSwitchCallbackDataPre = 'cc_whiteswitch';
    private $unwhitelistSwitchCallbackDataPre = 'cc_unwhiteswitch';
    private $blackCallbackDataPre = "cc_black";
    private $unblackCallbackDataPre = "cc_unblack";
    private $potatoContactUid;
    private $potatoContactPhone;
    private $potatoContactFirstName;
    private $potatoContactLastName = null;
    private $potatoSendFirstName;
    private $potatoSendLastName;
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
    private $menuNoMemberText = "He is not a member of our system and you can not perform this operation.";

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
        $this->webhook = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
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
     * 获取验证码.
     *
     * @return string
     */
    public function makeCode()
    {

        $letters = 'bcdfghjklmnpqrstvwxyz';
        $vowels = 'aeiou';
        $code = '';
        for ($i = 0; $i < self::CODE_LENGTH ; ++$i) {
            if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0, 10) > 9) {
                $code .= $vowels[mt_rand(0, 4)];
            } else {
                $code .= $letters[mt_rand(0, 20)];
            }
        }
        return $code;
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
     * 欢迎.
     */
    public function potatoWellcome()
    {
        $this->setKeyboard();
        // 发送操作菜单.
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'reply_to_message_id' => 0,
            'text' => $this->getWellcomeText(),
            'reply_markup' => [
                'keyboard' => $this->keyboard,
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
     * 发送菜单.
     */
    public function sendMenulist()
    {
        $this->callPersonData = User::findOne(['potato_user_id' => $this->potatoUid]);
        $this->calledPersonData = User::findOne(['potato_user_id' => $this->potatoContactUid]);
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
        } elseif (!empty($this->callPersonData) && ($this->potatoUid == $this->potatoContactUid)){
            if ($this->callPersonData->whitelist_switch == 0) {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getWhiteSwitchText(),
                    'data' => implode('-', array($this->whitelistSwitchCallbackDataPre, $this->potatoUid, $this->potatoContactPhone)),
                ];
            } else {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getUnwhiteSwitchText(),
                    'data' => implode('-', array($this->unwhitelistSwitchCallbackDataPre, $this->potatoUid, $this->potatoContactPhone)),
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
                'inline_markup' => [
                    $inlineKeyboard,
                ]
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
                'text' => $this->getCallText(),
                'callback_data' => implode('-', array($this->callCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone, $this->potatoContactLastName.$this->potatoContactFirstName, $this->potatoSendLastName.$this->potatoSendFirstName)),
            ];

            // 检查是否加了呼叫人到自己到白名单.
            $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
            $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid'=> $this->calledPersonData->id]);
            if ($whiteRes) {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getUnwhiteText(),
                    'data' => implode('-', array($this->unwhiteCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone)),
                ];
            } else {
                $whiteMenu = [
                    'type' => 0,
                    'text' => $this->getWhiteText(),
                    'data' => implode('-', array($this->whiteCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone)),
                ];
            }
            // 黑名单按钮.
            if ($blackRes) {
                $blackMenu = [
                    'type' => 0,
                    'text' => $this->getUnblackText(),
                    'callback_data' => implode('-', array($this->unblackCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone)),
                ];
            } else {
                $blackMenu = [
                    'type' => 0,
                    'text' => $this->getBlackText(),
                    'callback_data' => implode('-', array($this->blackCallbackDataPre, $this->potatoContactUid, $this->potatoContactPhone)),
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
                'inline_markup' => [
                    $inlineKeyboard,
                ]
            ];
        }

        return $this->sendPotatoData();
    }

    /**
     * 呼叫本人联系方式.
     *
     * @return mixed
     */
    public function callPersonPhone($nickname)
    {
        $result = false;
        $this->language = $this->callPersonData->language;
        $nexmoData = [
            "api_key" => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'lg' => $this->language,
            'repeat' => $this->repeat,
            'voice' => $this->voice,
            'to'  => '',
            'from' => '',
            'text' => $this->potatoSendFirstName.$this->translateLanguage('呼叫您上线potato!'),
        ];
        $numberArr = UserPhone::find()->select(['id', 'phone_country_code', 'user_phone_number'])->where(['user_id' => $this->calledPersonData->id])->orderBy('id asc')->all();
        foreach ($numberArr as $key => $number) {
            if (empty($this->callPersonData->country_code) || empty($this->callPersonData->phone_number)) {
                $this->callPersonData->country_code = $number->phone_country_code;
                $this->callPersonData->phone_number = $number->user_phone_number;
            }
            // 呼叫本人设置的联系方式.
            $nexmoData['to'] = $number->phone_country_code.$number->user_phone_number;
            if (empty($number->phone_country_code) || empty($number->user_phone_number)) {
                continue;
            }

            $res = $this->callPerson($nexmoData);
            if ($res['status']) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->getCallText().$nickname.$this->getSuccessText(),
                ];
                $this->sendPotatoData();
                // 保存通话记录.
                $this->saveCallRecordData($res['status'], $nexmoData['to']);
                $result = true;
                break;
            }

            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->getCallText().$nickname.$this->getFailureText().' '.$this->translateLanguage($res['message']),
            ];
            $this->sendPotatoData();
        }

        return $result;
    }

    /**
     * 呼叫本人的紧急联系方式.
     *
     * @return mixed
     */
    public function callPersonUrgentPhone($nickname)
    {
        $result = false;
        $this->language = $this->callPersonData->language;
        $nexmoData = [
            "api_key" => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'lg' => $this->language,
            'repeat' => $this->repeat,
            'voice' => $this->voice,
            'to'  => '',
            'from' => '',
            'text' => $this->translateLanguage('请转告'.$nickname.', 上线potato!'),
        ];
        $numberArr = UserGentContact::find()->select(['id', 'contact_country_code', 'contact_phone_number', 'contact_nickname'])->where(['user_id' => $this->calledPersonData->id])->orderBy('id asc')->all();
        foreach ($numberArr as $key => $number) {
            $nexmoData['to'] = $number->contact_country_code.$number->contact_phone_number;
            if (empty($number->contact_country_code) || empty($number->contact_phone_number)) {
                continue;
            }

            $res = $this->callPerson($nexmoData);
            if ($res['status']) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->translateLanguage('呼叫"'.$nickname.'"的紧急联系人"'.$number->contact_nickname.'", 成功!'),
                ];
                $this->sendPotatoData();
                // 保存通话记录.
                $this->saveCallRecordData($res['status'], '', $nexmoData['to'] );
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * 呼叫potato账号.
     */
    public function callPotatoPerson()
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
            $nickname = $this->potatoContactLastName.$this->potatoContactFirstName;
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

            $res = $this->callPersonPhone($nickname);
            // 本人联系方式呼叫失败，尝试呼叫本人的紧急联系方式.
            if (!$res) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->translateLanguage('呼叫"'.$nickname.'"失败, 尝试呼叫"'.$nickname.'"的紧急联系人, 请稍后!'),
                ];
                $this->sendPotatoData();
                $res = $this->callPersonUrgentPhone($nickname);
            }

            if (!$res) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => $this->translateLanguage('抱歉本次呼叫"' . $nickname . '"失败，请稍后再试, 或尝试其他方式联系' . $user->nickname . '!'),
                ];
                $this->sendPotatoData();
            }

            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->potatoContactLastName.$this->potatoContactFirstName.$this->getIsNotMemberText(),
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
        $res = $this->sendPotatoData($this->nexmoUrl);
        $res = json_decode($res, true);
        // 保存通话记录.
        if ($res['status'] != 0) {
            $result['status'] = false;
        }

        return $result;
    }

    /**
     * 保存通话记录.
     */
    public function saveCallRecordData($status, $personPhone = '', $urgentPhone = '')
    {
        $callRecord = new CallRecord();
        $callRecord->active_call_uid = $this->callPersonData->id;
        $callRecord->unactive_call_uid = $this->calledPersonData->id;
        $callRecord->active_account = $this->potatoContactLastName.$this->potatoContactFirstName;
        $callRecord->unactive_account = $this->potatoSendLastName.$this->potatoSendFirstName;
        $callRecord->active_nickname = $this->callPersonData->nickname;
        $callRecord->unactive_nickname = $this->calledPersonData->nickname;
        $callRecord->contact_number = $this->callPersonData->country_code.$this->callPersonData->phone_number;

        $callRecord->unactive_contact_number = !empty($personPhone) ? $personPhone : $urgentPhone;
        $callRecord->status = $status ? 0 : 1;
        $callRecord->call_time = time();
        $callRecord->type = ($urgentPhone) ? 1 : 0;;
        $res = $callRecord->save();

        return $res ? true : false;
    }

    /**
     * 绑定操作.
     */
    public function bindPotatoData()
    {   
        if(empty($this->bindCode)){
            return  $this->addError('bindCode',$this->getCodeEmptyText());
        }
        $user = User::findOne(Yii::$app->user->id);
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
            $user->potato_user_id = $dataArr['1'];
            $user->potato_number = $dataArr['2'];
            $user->potato_name = $dataArr['3'];
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
    public function unbundlePotatoData()
    {
        $user = User::findOne(Yii::$app->user->id);
        $user->potato_user_id = 0;
        $user->potato_number = 0;
        return $user->save();
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