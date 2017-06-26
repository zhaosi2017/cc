<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;
use app\modules\home\models\WhiteList;

class Telegram extends Model
{

    const CODE_LENGTH = 5;

    private $telegramText = '操作菜单';
    private $startText = '开始操作, 请稍后!';
    private $wellcomeText = '欢迎';
    private $keyboardText = '分享自己名片';
    private $callText = "呼叫";
    private $firstText = '/start';
    private $webhook;
    private $nexmoUrl = "https://api.nexmo.com/tts/json";
    private $apiKey = '85704df7';
    private $apiSecret = '755026fdd40f34c2';
    private $language = 'zh-cn';
    private $repeat = 3;
    private $voice = 'male';
    // 是否是紧急呼叫.
    private $isUrgentCall = 0;
    private $callCallbackDataPre = 'cc_call';
    private $whiteCallbackDataPre = 'cc_white';
    private $unwhiteCallbackDataPre = 'cc_unwhite';
    private $whitelistSwitchCallbackDataPre = 'cc_whiteswitch';
    private $unwhitelistSwitchCallbackDataPre = 'cc_unwhiteswitch';
    private $blackCallbackDataPre = "cc_black";
    private $unblackCallbackDataPre = "cc_unblack";
    private $whiteText = '加白名单';
    private $unwhiteText = '解除白名单';
    private $blackText = "加黑名单";
    private $unblackText = "解除黑名单";
    private $whiteSwitchText = '开启白名单';
    private $unwhiteSwitchText = '关闭白名单';

    private $code;
    private $bindCode;
    private $telegramUid;
    private $telegramFirstName;
    private $telegramLastName;
    private $telegramContactUid;
    private $telegramContactPhone;
    private $telegramContactFirstName;
    private $telegramContactLastName;
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
        $this->webhook = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
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
        // 查询是否绑定.
        $dealData = [
            Yii::$app->params['telegram_pre'],
            $this->telegramContactUid,
            $this->telegramContactPhone,
        ];

        $dealData = implode('-', $dealData);
        $this->code = $this->makeCode();
        $telegramData = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['telegram']));
        // 验证码过期时间半小时.
        Yii::$app->redis->setex($this->code, 30*60, $telegramData);
        $this->code = $this->code.'  [请在callu平台输入该验证码, 完成绑定操作!]';
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
     * @return mixeds
     */
    public function getTelegramText()
    {
        return $this->telegramText;
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
        return $this->language;
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

    public function getWhiteText()
    {
        return $this->whiteText;
    }

    public function getUnwhiteText()
    {
        return $this->unwhiteText;
    }

    public function getBlackText()
    {
        return $this->blackText;
    }

    public function getUnblackText()
    {
        return $this->unblackText;
    }

    public function getWhiteSwitchText()
    {
        return $this->whiteSwitchText;
    }

    public function getUnwhiteSwitchText()
    {
        return $this->unwhiteSwitchText;
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
     * @return string
     */
    public function getKeyboardText()
    {
        return $this->keyboardText;
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
        return $this->startText;
    }

    /**
     * 欢迎.
     */
    public function getWellcomeText()
    {
        return $this->wellcomeText;
    }

    /**
     * 返回回调数据.
     */
    public function getCallbackQuery()
    {
        return $this->callbackQuery;
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
                    "text"=> $this->keyboardText,
                    "request_contact"=> true,
                ]
            ]
        ];
        // 发送操作菜单.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'reply_to_message_id' => 0,
            'text' => $this->wellcomeText,
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
            ];
        } elseif (empty($this->callPersonData) && ($this->telegramUid != $this->telegramContactUid)) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '请先分享自己的名片到机器人，完成绑定操作!',
            ];
        } elseif (!empty($this->callPersonData) && ($this->telegramUid == $this->telegramContactUid)){
            if ($this->callPersonData->whitelist_switch == 0) {
                $whiteMenu = [
                    'text' => $this->whiteSwitchText,
                    'callback_data' => implode('-', array($this->whitelistSwitchCallbackDataPre, $this->telegramUid, $this->callPersonData->telegram_number)),
                ];
            } else {
                $whiteMenu = [
                    'text' => $this->unwhiteSwitchText,
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
                'text' => $this->telegramText,
                'reply_markup' => [
                    'inline_keyboard' => $inlineKeyboard,
                ]
            ];
        } else {
            if (empty($this->calledPersonData)) {
                $sendData['chat_id'] = $this->telegramUid;
                $sendData['text'] = '他/她不是我们系统会员，不能执行该操作!';
                $this->sendData = $sendData;
                return $this->sendTelegramData();
            }
            $callMenu = [
                'text' => $this->callText,
                'callback_data' => implode('-', array($this->callCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
            ];

            // 检查是否加了呼叫人到自己到白名单.
            $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
            $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
            if ($whiteRes) {
                $whiteMenu = [
                    'text' => $this->unwhiteText,
                    'callback_data' => implode('-', array($this->unwhiteCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
                ];
            } else {
                $whiteMenu = [
                    'text' => $this->whiteText,
                    'callback_data' => implode('-', array($this->whiteCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
                ];
            }
            // 黑名单按钮.
            if ($blackRes) {
                $blackMenu = [
                    'text' => $this->unblackText,
                    'callback_data' => implode('-', array($this->unblackCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone)),
                ];
            } else {
                $blackMenu = [
                    'text' => $this->blackText,
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
                'text' => $this->telegramText,
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
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = '您不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }


        if ($this->callPersonData->whitelist_switch == 1) {
            $sendData['text'] = '已经开启了白名单功能!';
        } else {
            $this->callPersonData->whitelist_switch=1;
            $res = $this->callPersonData->save();
            $res ? ($sendData['text'] = '开启白名单功能成功!') : ($sendData['text'] = '开启白名单功能失败!');
        }

        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 关闭白名单功能.
     */
    public function disableWhiteSwith()
    {
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = '您不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }


        if ($this->callPersonData->whitelist_switch == 0) {
            $sendData['text'] = '已经关闭了白名单功能!';
        } else {
            $this->callPersonData->whitelist_switch=0;
            $res = $this->callPersonData->save();
            $res ? ($sendData['text'] = '关闭白名单功能成功!') : ($sendData['text'] = '关闭白名单功能失败!');
        }

        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 加入白名单.
     */
    public function joinWhiteList()
    {
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = '您不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = '他/她不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid'=> $this->calledPersonData->id]);
        if ($whiteRes) {
            $sendData['text'] = '已经在白名单里!';
        } else {
            $whiteRes = new WhiteList();
            $whiteRes->uid = $this->callPersonData->id;
            $whiteRes->white_uid = $this->calledPersonData->id;
            $res = $whiteRes->save();
            $res ? ($sendData['text'] = '加入白名单成功!') : ($sendData['text'] = '加入白名单失败!');

            $res = WhiteList::findOne(['uid' => $this->calledPersonData->id, 'white_uid'=> $this->callPersonData->id]);
            if (empty($res)) {
                $this->sendData = [
                    'chat_id' => $this->telegramContactUid,
                    'text' => $this->telegramLastName . $this->telegramFirstName . '已经将您加入白名单, 您也可以点击下面按钮加他到您的白名单!',
                ];
                $this->sendTelegramData();
                $bindMenu = [
                    'text' => $this->whiteText,
                    'callback_data' => implode('-', array($this->whiteCallbackDataPre, $this->telegramUid, $this->callPersonData->telegram_number)),
                ];
                $inlineKeyboard = [
                    [
                        $bindMenu
                    ]
                ];
                $this->sendData = [
                    'chat_id' => $this->telegramContactUid,
                    'text' => $this->telegramText,
                    'reply_markup' => [
                        'inline_keyboard' => $inlineKeyboard,
                    ]
                ];
                $this->sendTelegramData();
            }
        }


        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 解除白名单.
     */
    public function unbindWhiteList()
    {
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = '您不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = '他/她不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $whiteRes = WhiteList::findOne(['uid' => $this->callPersonData->id, 'white_uid' => $this->calledPersonData->id]);
        if ($whiteRes) {
            $res = $whiteRes->delete();
            $res ? ($sendData['text'] = '解除白名单成功!') : ($sendData['text'] = '解除白名单失败!');
        } else {
            $sendData['text'] = '不在白名单!';
        }


        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 加入黑名单.
     */
    public function joinBlackList()
    {
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = '您不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = '他/她不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid'=> $this->calledPersonData->id]);
        if ($blackRes) {
            $sendData['text'] = '已经在黑名单里!';
        } else {
            $blackRes = new BlackList();
            $blackRes->uid = $this->callPersonData->id;
            $blackRes->black_uid = $this->calledPersonData->id;
            $res = $blackRes->save();
            $res ? ($sendData['text'] = '加入黑名单成功!') : ($sendData['text'] = '加入黑名单失败!');
        }


        $this->sendData = $sendData;
        return $this->sendTelegramData();
    }

    /**
     * 解除白名单.
     */
    public function unbindBlackList()
    {
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $sendData = [
            'chat_id' => $this->telegramUid,
            'text' => '',
        ];

        $this->callPersonData = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (empty($this->callPersonData)) {
            $sendData['text'] = '您不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $this->calledPersonData = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if (empty($this->calledPersonData)) {
            $sendData['text'] = '他/她不是我们系统会员，不能执行该操作!';
            $this->sendData = $sendData;
            return $this->sendTelegramData();
        }

        $blackRes = BlackList::findOne(['uid' => $this->callPersonData->id, 'black_uid' => $this->calledPersonData->id]);
        if ($blackRes) {
            $res = $blackRes->delete();
            $res ? ($sendData['text'] = '解除黑名单成功!') : ($sendData['text'] = '解除黑名单失败!');
        } else {
            $sendData['text'] = '不在白名单!';
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
                'text' => '请先分享自己的名片到机器人，完成绑定操作!',
            ];
        } else {
            $this->setCode();
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->code,
            ];
        }

        $this->sendTelegramData();
        return $this->errorCode['success'];
    }

    /**
     * 呼叫telegram账号.
     */
    public function callTelegramPerson()
    {
        // 开始操作.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (!$res) {
            // 发送验证码，完成绑定.
            return $this->sendBindCode();
        } elseif ($this->telegramUid == $this->telegramContactUid) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '您已经完成了绑定操作!',
            ];
            $this->sendTelegramData();
            return $this->errorCode['success'];
        }
        $this->callPersonData = $res;
        $user = User::findOne(['telegram_user_id' => $this->telegramContactUid]);
        if ($user) {
            $this->calledPersonData = $user;
            $nickname = !empty($user->nickname) ? $user->nickname : '他/她';

            // 黑名单检查.
            $res = $this->blackList();
            if (!$res) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '您在'.$nickname.'的黑名单列表内, 不能呼叫!',
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
                        'text' => '您不在'.$nickname.'的白名单列表内, 不能呼叫!',
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
                    'text' => '呼叫"'.$nickname.'"失败! '.$res['message'],
                ];
                $this->sendTelegramData();
                return $this->errorCode['success'];
            }
            // 呼叫本人.
            $nexmoData = [
                "api_key" => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'lg' => $this->language,
                'repeat' => $this->repeat,
                'voice' => $this->voice,
                'to'    => $user->country_code.$user->phone_number,
                'from'  => $this->callPersonData->country_code.$this->callPersonData->phone_number,
                'text' => $this->telegramContactLastName.$this->telegramContactFirstName.'在telegram上找你!',
            ];

            if (empty($user->phone_number) || empty($user->country_code)) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => $nickname.'的联系方式设置有问题, 不能呼叫!',
                ];
                $this->sendTelegramData();
            } else {
                $res = $this->callPerson($nexmoData);
                if ($res['status']) {
                    $this->sendData = [
                        'chat_id' => $this->telegramUid,
                        'text' => '呼叫"'.$nickname.'"成功!',
                    ];
                    $this->sendTelegramData();
                    // 保存通话记录.
                    $this->saveCallRecordData($res['status']);
                    return $this->errorCode['success'];
                }

                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '呼叫"'.$nickname.'"失败! '.$res['message'],
                ];
                $this->sendTelegramData();
            }

            if (empty($user->urgent_contact_number_one) && empty($user->urgent_contact_number_two)) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '抱歉"'.$nickname.'"没有设置紧急联系人, 本次呼叫失败，请稍后再试, 或尝试其他方式联系"'.$user->nickname.'"!',
                ];
                $this->sendTelegramData();

                // 保存通话记录.
                $this->saveCallRecordData($res['status']);
                return $this->errorCode['success'];
            }

            if (!empty($user->urgent_contact_number_one)) {
                $this->isUrgentCall = 1;
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '尝试呼叫"'.$nickname.'"的紧急联系人"'.$user->urgent_contact_person_one.'", 请稍后!',
                ];
                $this->sendTelegramData();
                // 尝试呼叫紧急联系人一.
                $nexmoData['to'] = $user->urgent_contact_one_country_code.$user->urgent_contact_number_one;
                $res = $this->callPerson($nexmoData);
                if ($res['status']) {
                    $this->sendData = [
                        'chat_id' => $this->telegramUid,
                        'text' => '呼叫"'.$nickname.'"的紧急联系人"'.$user->urgent_contact_person_one.'", 成功!',
                    ];
                    $this->sendTelegramData();
                    // 保存通话记录.
                    $this->saveCallRecordData($res['status']);
                    return $this->errorCode['success'];
                }
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '呼叫"'.$nickname.'"的紧急联系人"'.$user->urgent_contact_person_one.'"失败! '.$res['message'],
                ];
                $this->sendTelegramData();
            }

            if (!empty($user->urgent_contact_number_two)) {
                $this->isUrgentCall = 2;
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '尝试呼叫"'.$nickname.'"的紧急联系人"'.$user->urgent_contact_person_two.'", 请稍后!',
                ];
                $this->sendTelegramData();
                // 尝试呼叫紧急联系人一.
                $nexmoData['to'] = $user->urgent_contact_two_country_code.$user->urgent_contact_number_two;
                $res = $this->callPerson($nexmoData);
                if ($res['status']) {
                    $this->sendData = [
                        'chat_id' => $this->telegramUid,
                        'text' => '呼叫"'.$nickname.'"的紧急联系人"'.$user->urgent_contact_person_two.'", 成功!',
                    ];
                    $this->sendTelegramData();
                    // 保存通话记录.
                    $this->saveCallRecordData($res['status']);
                    return $this->errorCode['success'];
                }
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '呼叫"'.$nickname.'"的紧急联系人"'.$user->urgent_contact_person_two.'"失败! '.$res['message'],
                ];
                $this->sendTelegramData();
                // 保存失败的通话记录.
                $this->saveCallRecordData($res['status']);
            }

            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '抱歉本次呼叫"'.$nickname.'"失败，请稍后再试, 或尝试其他方式联系'.$user->nickname.'!',
            ];
            $this->sendTelegramData();
            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '他/她不是我们系统会员，不能执行该操作!',
            ];
            $this->sendTelegramData();
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
                    $res['message'] = '呼叫超出本人设置的限制次数';
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
     * 保存通话记录.
     */
    public function saveCallRecordData($status)
    {
        $callRecord = new CallRecord();
        $callRecord->active_call_uid = $this->callPersonData->id;
        $callRecord->unactive_call_uid = $this->calledPersonData->id;
        $callRecord->active_account = $this->callPersonData->account;
        $callRecord->unactive_account = $this->calledPersonData->account;
        $callRecord->active_nickname = $this->callPersonData->nickname;
        $callRecord->unactive_nickname = $this->calledPersonData->nickname;
        $callRecord->contact_number = $this->callPersonData->country_code.$this->callPersonData->phone_number;

        if ($this->isUrgentCall == 1) {
            $callRecord->unactive_contact_number = $this->calledPersonData->urgent_contact_one_country_code.$this->calledPersonData->urgent_contact_number_one;
        } elseif ($this->isUrgentCall == 2) {
            $callRecord->unactive_contact_number = $this->calledPersonData->urgent_contact_two_country_code.$this->calledPersonData->urgent_contact_number_two;
        } else {
            $callRecord->unactive_contact_number = $this->calledPersonData->country_code.$this->calledPersonData->phone_number;
        }
        $callRecord->status = $status ? 0 : 1;
        $callRecord->call_time = time();
        $callRecord->type = ($this->isUrgentCall > 0) ? 1 : 0;
        $res = $callRecord->save();

        return $res ? true : false;
    }

    /**
     * 绑定操作.
     */
    public function bindTelegramData()
    {   
        if(empty($this->bindCode)){
            return  $this->addError('bindCode','验证码为空');
        }
        $user = User::findOne(Yii::$app->user->id);
        if (!Yii::$app->redis->exists($this->bindCode)) {
            $this->addError('bindCode', '验证码错误!');
        } else {
            $telegramData = Yii::$app->redis->get($this->bindCode);
        }
        if (empty($telegramData)) {
            return $this->addError('bindCode', '验证码错误!');
        }

        $data = Yii::$app->security->decryptByKey(base64_decode($telegramData), Yii::$app->params['telegram']);
        $dataArr = explode('-', $data);
        if ($dataArr[0] == Yii::$app->params['telegram_pre']) {
            $user->telegram_user_id = $dataArr['1'];
            $user->telegram_number = $dataArr['2'];
            $res = $user->save();
            if ($res) {
                Yii::$app->redis->del($this->bindCode);
            }
            return $res;
        } else {
            return $this->addError('bindCode', '验证码错误!');
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
    public function sendTelegramData($url = null)
    {
        if (empty($this->telegramUid)) {
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