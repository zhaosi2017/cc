<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;

class Telegram extends Model
{

    private $queryText = "查询";
    private $telegramText = '操作菜单';
    private $callText = "呼叫";
    private $bindText = '绑定账号';
    private $startText = '开始操作, 请稍后!';
    private $wellcomeText = '欢迎';
    private $keyboardText = '分享自己名片';
    private $firstText = '/start';
    private $webhook;
    private $nexmoUrl = "https://api.nexmo.com/tts/json";
    private $apiKey = '85704df7';
    private $apiSecret = '755026fdd40f34c2';
    private $language = 'zh-cn';
    private $repeat = 3;
    private $voice = 'male';
    // 是否是紧急呼叫.
    private $isUrgentCall = false;

    private $code;
    private $bindCode;
    private $telegramUid;
    private $telegramContactUid;
    private $telegramContactPhone;
    private $telegramContactFirstName;
    private $telegramContactLastName;
    private $queryCallbackDataPre = 'cc_query';
    private $callCallbackDataPre = 'cc_call';
    private $bindCallbackDataPre = 'cc_bind';
    private $queryCallbackData;
    private $callCallbackData;
    private $bindCallbackData;
    private $callbackQuery;
    private $callPersonData;
    private $calledPersonData;

    private $queryMenu;
    private $callMenu;
    private $bindMenu;
    private $keyboard;
    private $inlineKeyboard;
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
    public function setTelegramContactUid($value)
    {
        $this->telegramContactUid = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramContactPhone($value)
    {
        $this->telegramContactPhone = $value;
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
     * 设置验证码.
     */
    public function setCode()
    {
        // 查询是否绑定自己的账号.
        if ($this->telegramUid != $this->telegramContactUid) {
            return 'error_code :'.$this->errorCode['not_yourself'];
        }

        // 查询是否绑定.
        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if ($res) {
            return 'error_code :'.$this->errorCode['exist'];
        } else {
            $dealData = [
                Yii::$app->params['telegram_pre'],
                $this->telegramContactUid,
                $this->telegramContactPhone,
            ];

            $dealData = implode('-', $dealData);
            $charid = strtoupper(md5(uniqid(mt_rand(), true)));
            $this->code = substr($charid, 0, 8);
            $telegramData = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['telegram']));
            // 验证码过期时间半小时.
            Yii::$app->redis->setex($this->code, 30*60, $telegramData);
        }
    }

    /**
     * @param $value
     */
    public function setBindCode($value)
    {
        $this->bindCode = $value;
    }

    /**
     * 设置查询菜单.
     */
    public function setQueryMenu()
    {
        $this->setQueryCallbackData();
        $this->queryMenu = array(
            'text' => $this->queryText,
            'callback_data' => $this->queryCallbackData,
        );
    }

    /**
     * 设置呼叫菜单.
     */
    public function setCallMenu()
    {
        $this->setCallCallbackData();
        $this->callMenu = array(
            'text' => $this->callText,
            'callback_data' => $this->callCallbackData,
        );
    }

    /**
     * 设置绑定菜单.
     */
    public function setBindMenu()
    {
        $this->setBindCallbackData();
        $this->bindMenu = array(
            'text' => $this->bindText,
            'callback_data' => $this->bindCallbackData,
        );
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
     * 设置查询回调参数.
     */
    public function setQueryCallbackData()
    {
        $this->queryCallbackData = implode('-', array($this->queryCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone));
    }

    /**
     * 设置呼叫回调参数.
     */
    public function setCallCallbackData()
    {
        $this->callCallbackData = implode('-', array($this->callCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone));
    }

    /**
     * 设置绑定回调参数.
     */
    public function setBindCallbackData()
    {
        $this->bindCallbackData = implode('-', array($this->bindCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone));
    }

    /**
     * 设置菜单.
     *
     * @return json.
     */
    public function setInlineKeyboard()
    {
        // 查询是否绑定.
        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if ($res) {
            $this->setQueryMenu();
            $this->setCallMenu();
            $this->inlineKeyboard = [
                [
                    $this->queryMenu,
                    $this->callMenu,
                ]
            ];
        } else {
            $this->setBindMenu();
            $this->setQueryMenu();
            $this->setCallMenu();
            if ($this->telegramContactUid == $this->telegramUid) {
                $this->inlineKeyboard = [
                    [
                        $this->bindMenu,
                    ],
                    [
                        $this->queryMenu,
                        $this->callMenu,
                    ]
                ];
            } else {
                $this->inlineKeyboard = [
                    [
                        $this->queryMenu,
                        $this->callMenu,
                    ]
                ];
            }
        }
    }

    /**
     * @param $value
     */
    public function setSendData($value)
    {
        $this->sendData = $value;
    }

    /**
     * @param $value
     */
    public function setCallbackQuery($value)
    {
        $this->callbackQuery = $value;
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
        return $this->callText;
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
    public function getTelegramUid()
    {
        return $this->telegramUid;
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
     * 获取查询菜单.
     */
    public function getQueryMenu()
    {
        return $this->queryMenu;
    }

    /**
     * 获取呼叫菜单.
     */
    public function getCallMenu()
    {
        return $this->callMenu;
    }

    /**
     * 获取绑定菜单.
     */
    public function getBindMenu()
    {
        return $this->bindMenu;
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
        return $this->keyboardText;
    }

    /**
     * 获取查询回调参数.
     */
    public function getQueryCallbackData()
    {
        return $this->queryCallbackData;
    }

    /**
     * 获取呼叫回调参数
     */
    public function getCallCallbackData()
    {
        return $this->callCallbackData;
    }

    /**
     * 获取绑定回调参数.
     */
    public function getBindCallbackData()
    {
        return $this->bindCallbackData;
    }

    /**
     * @return string
     */
    public function getQueryCallbackDataPre()
    {
        return $this->queryCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getCallCallbackDataPre()
    {
        return $this->callCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getBindCallbackDataPre()
    {
        return $this->bindCallbackDataPre;
    }

    /**
     * @return mixed
     */
    public function getSendData()
    {
        return $this->sendData;
    }

    /**
     * @return mixed
     */
    public function getCallbackQuery()
    {
        return $this->callbackQuery;
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
     * 设置菜单.
     *
     * @return json.
     */
    public function getInlineKeyboard()
    {
        return $this->inlineKeyboard;
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
     * 欢迎.
     */
    public function telegramWellcome()
    {
        $this->setKeyboard();
        // 发送操作菜单.
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'reply_to_message_id' => 0,
            'text' => $this->wellcomeText,
            'reply_markup' => [
                'keyboard' => $this->keyboard,
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
        $this->setInlineKeyboard();
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->telegramText,
            'reply_markup' => [
                'inline_keyboard' => $this->inlineKeyboard,
            ]
        ];

        return $this->sendTelegramData();
    }

    /**
     * 发送绑定telegram账号的验证码.
     */
    public function sendBindCode()
    {
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();

        $callbackQuery = explode('-', $this->callbackQuery);
        $this->telegramContactUid = $callbackQuery[1];
        $this->telegramContactPhone = $callbackQuery[2];
        $this->setCode();
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->code,
        ];
        $this->sendTelegramData();
        return $this->errorCode['success'];
    }

    /**
     * 查询telegram账号.
     */
    public function queryTelegramData()
    {
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();
        $contactArr = explode('-', $this->callbackQuery);
        // 查询是否绑定.
        $res = User::findOne(['telegram_user_id' => $contactArr[1]]);
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $res ? $this->errorMessage['exist'] : $this->errorMessage['noexist'],
        ];
        $this->sendTelegramData();
        return $this->errorCode['success'];
    }

    /**
     * 呼叫telegram账号.
     */
    public function callTelegramPerson()
    {
        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if (!$res) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '你不是我们系统会员，不能执行该操作!',
            ];
            $this->sendTelegramData();
        }
        $this->callPersonData = $res;
        $this->sendData = [
            'chat_id' => $this->telegramUid,
            'text' => $this->startText,
        ];
        $this->sendTelegramData();
        $contactArr = explode('-', $this->callbackQuery);
        $user = User::findOne(['telegram_user_id' => $contactArr[1]]);
        $this->calledPersonData = $user;
        if ($user) {
            $nickname = !empty($user->nickname) ? $user->nickname : '他/她';

            // 呼叫本人.
            $nexmoData = [
                "api_key" => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'lg' => $this->language,
                'repeat' => $this->repeat,
                'voice' => $this->voice,
                'to'    => $user->country_code.$user->phone_number,
                'text' => $this->telegramContactLastName.$this->telegramContactFirstName.'在telegram上找你!',
            ];

            if (empty($user->phone_number) || empty($user->country_code)) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => $nickname.'的联系方式设置有问题, 不能呼叫!',
                ];
                $this->sendTelegramData();
            } else {
                $res = $this->callPerson($nickname, $nexmoData);
                if ($res['status']) {
                    return $this->errorCode['success'];
                }
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '呼叫：'.$nickname.'失败! '.$res['message'],
                ];
                $this->sendTelegramData();
            }

            if (empty($user->urgent_contact_number_one) && empty($user->urgent_contact_number_two)) {
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '抱歉: '.$nickname.'没有设置紧急联系人, 本次呼叫失败，请稍后再试, 或尝试其他方式联系'.$user->nickname.'!',
                ];
                $this->sendTelegramData();

                return $this->errorCode['success'];
            }

            if (!empty($user->urgent_contact_number_one)) {
                $this->isUrgentCall = true;
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '尝试呼叫: '.$nickname.'的紧急联系人:'.$user->urgent_contact_person_one.', 请稍后!',
                ];
                $this->sendTelegramData();
                // 尝试呼叫紧急联系人一.
                $nexmoData['to'] = $user->urgent_contact_one_country_code.$user->urgent_contact_number_one;
                $res = $this->callPerson($user->urgent_contact_person_one, $nexmoData);
                if ($res['status']) {
                    return $this->errorCode['success'];
                }
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '呼叫：'.$nickname.'的紧急联系人:'.$user->urgent_contact_person_one.'失败! '.$res['message'],
                ];
                $this->sendTelegramData();
            }

            if (!empty($user->urgent_contact_number_two)) {
                $this->isUrgentCall = true;
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '尝试呼叫: '.$nickname.'的紧急联系人:'.$user->urgent_contact_person_two.', 请稍后!',
                ];
                $this->sendTelegramData();
                // 尝试呼叫紧急联系人一.
                $nexmoData['to'] = $user->urgent_contact_two_country_code.$user->urgent_contact_number_two;
                $res = $this->callPerson($user->urgent_contact_person_two, $nexmoData);
                if ($res['status']) {
                    return $this->errorCode['success'];
                }
                $this->sendData = [
                    'chat_id' => $this->telegramUid,
                    'text' => '呼叫：'.$nickname.'的紧急联系人:'.$user->urgent_contact_person_two.'失败! '.$res['message'],
                ];
                $this->sendTelegramData();
            }

            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '抱歉本次呼叫: '.$nickname.'失败，请稍后再试, 或尝试其他方式联系'.$user->nickname.'!',
            ];
            $this->sendTelegramData();
            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => $this->errorMessage['noexist'],
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
            $callKey = $this->callPersonData->country_code.$this->callPersonData->phone_number;
            if (!Yii::$app->redis->exists($this->telegramUid)) {
                Yii::$app->redis->hset($this->telegramUid, 'total', $this->calledPersonData->un_call_number);
                Yii::$app->redis->hset($this->telegramUid, 'person', $this->calledPersonData->un_call_by_same_number);
                Yii::$app->redis->hset($this->telegramUid, $callKey, 1);
                Yii::$app->redis->expire($this->telegramUid, $this->calledPersonData->long_time * 60);
            } else {
                $totalNum = Yii::$app->redis->hmget($this->telegramUid, 'total');
                $personNum = Yii::$app->redis->hmget($this->telegramUid, 'person');
                $pnum = Yii::$app->redis->hexists($this->telegramUid, $callKey) ? Yii::$app->redis->hget($this->telegramUid, $callKey) : 0;
                if ($totalNum >= $this->calledPersonData->un_call_number || $pnum >= $personNum) {
                    $res['status'] = false;
                    $res['message'] = '呼叫超出本人设置的限制次数';
                    return $res;
                }
                Yii::$app->redis->hincrby($this->telegramUid, 'total', 1);
                Yii::$app->redis->hexists($this->telegramUid, $callKey) ? Yii::$app->redis->hincrby($this->telegramUid, $callKey, 1) : Yii::$app->redis->hset($this->telegramUid, $callKey, 1);
            }
        }

        return $res;
    }

    /**
     * @param string $nickname 呼叫人.
     * @param arra   $data     数据.
     *
     * @return boolean
     */
    public function callPerson($nickname, $data)
    {
        // 呼叫限制检查.
        $res = $this->callLimit();
        if ($res['status']) {
            return $res;
        }

        $result = [
            'status' => true,
            'message' => '',
        ];
        $this->sendData = $data;
        $res = $this->sendTelegramData($this->nexmoUrl);
        $res = json_decode($res, true);
        $this->saveCallRecordData($res['status']);
        // 保存通话记录.
        if ($res['status'] == 0) {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '呼叫: '.$nickname.'成功!',
            ];
            $this->sendTelegramData();
        } else {
            $this->sendData = [
                'chat_id' => $this->telegramUid,
                'text' => '呼叫: '.$nickname.'失败!',
            ];
            $this->sendTelegramData();
            $result['status'] = false;
        }

        return $result;
    }

    /**
     * 保存通话记录.
     */
    public function saveCallRecordData($status)
    {
        $contactArr = explode('-', $this->callbackQuery);
        $activeUser = User::findOne(['telegram_user_id' => $this->telegramUid]);
        $unActiveUser = User::findOne(['telegram_user_id' => $contactArr[1]]);
        $callRecord = new CallRecord();
        $callRecord->active_call_uid = $activeUser->id;
        $callRecord->unactive_call_uid = $contactArr['1'];
        $callRecord->active_account = $activeUser->account;
        $callRecord->unactive_account = $unActiveUser->account;
        $callRecord->active_nickname = $activeUser->nickname;
        $callRecord->unactive_nickname = $unActiveUser->nickname;
        $callRecord->contact_number = $activeUser->country_code.$activeUser->phone_number;
        $callRecord->unactive_contact_number = $unActiveUser->country_code.$unActiveUser->phone_number;
        $callRecord->status = $status;
        $callRecord->call_time = time();
        $callRecord->type = $this->isUrgentCall ? 1 : 0;
        $res = $callRecord->save();

        return $res ? true : false;
    }

    /**
     * 绑定操作.
     */
    public function bindTelegramData()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!Yii::$app->redis->exists($this->bindCode)) {
            $this->addError('bindCode', '无效的验证码!');
        } else {
            $telegramData = Yii::$app->redis->get($this->bindCode);
        }
        if (empty($telegramData)) {
            return $this->addError('bindCode', '无效的验证码!');
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
            return $this->addError('bindCode', '无效的验证码!');
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