<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;

class Potato extends Model
{

    const CODE_LENGTH = 5;

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
    private $isUrgentCall = 0;

    private $code;
    private $bindCode;
    private $potatoUid;
    private $shareRequestType = 4;
    private $potatoContactUid;
    private $potatoContactPhone;
    private $potatoContactFirstName;
    private $potatoContactLastName = null;
    private $potatoSendFirstName;
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
        // $this->webhook = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
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
        $telegramData = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['potato']));
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
     * 分享联系人名片请求方式.
     *
     * @return int
     */
    public function getShareRequestType()
    {
        return $this->shareRequestType;
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
            'text' => $this->wellcomeText,
            'reply_markup' => [
                'keyboard' => $this->keyboard,
            ]
        ];

        $this->sendPotatoData();
        return $this->errorCode['success'];
    }

    /**
     * 发送绑定telegram账号的验证码.
     */
    public function sendBindCode()
    {
        // 查询是否绑定自己的账号.
        if ($this->potatoUid != $this->potatoContactUid) {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => '请先分享自己的名片到机器人，完成绑定操作!',
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
     * 呼叫本人联系方式.
     *
     * @return mixed
     */
    public function callPersonPhone($nickname)
    {
        $result = false;
        $nexmoData = [
            "api_key" => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'lg' => $this->language,
            'repeat' => $this->repeat,
            'voice' => $this->voice,
            'to'  => '',
            'from' => '',
            'text' => $this->potatoSendFirstName.'在potato上找你!',
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
                    'text' => '呼叫"'.$nickname.'"成功!',
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
                'text' => '呼叫"'.$nickname.'"失败! '.$res['message'],
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
        $nexmoData = [
            "api_key" => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'lg' => $this->language,
            'repeat' => $this->repeat,
            'voice' => $this->voice,
            'to'  => '',
            'from' => '',
            'text' => $this->potatoSendFirstName.'在potato上找'.$nickname.', 请您及时转告!',
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
                    'text' => '呼叫"'.$nickname.'"的紧急联系人"'.$number->contact_nickname.'", 成功!',
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
     * 呼叫telegram账号.
     */
    public function callPotatoPerson()
    {
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'text' => $this->startText,
        ];
        $this->sendPotatoData();

        $res = User::findOne(['potato_user_id' => $this->potatoUid]);
        if (!$res) {
            // 发送验证码，完成绑定.
            return $this->sendBindCode();
        } elseif ($this->potatoUid == $this->potatoContactUid) {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => '你已经完成了绑定操作!',
            ];
            $this->sendPotatoData();
            return $this->errorCode['success'];
        }
        $this->callPersonData = $res;
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
                    'text' => '您在"'.$nickname.'"的黑名单列表内, 不能呼叫!',
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
                        'text' => '您不在' . $nickname . '的白名单列表内, 不能呼叫!',
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
                    'text' => '呼叫"'.$nickname.'"失败! '.$res['message'],
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
                    'text' => '呼叫"'.$nickname.'"失败, 尝试呼叫"'.$nickname.'"的紧急联系人, 请稍后!',
                ];
                $this->sendPotatoData();
                $res = $this->callPersonUrgentPhone($nickname);
            }

            if (!$res) {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'text' => '抱歉本次呼叫"' . $nickname . '"失败，请稍后再试, 或尝试其他方式联系' . $user->nickname . '!',
                ];
                $this->sendPotatoData();
            }

            return $this->errorCode['success'];
        } else {
            $this->sendData = [
                'chat_type' => 1,
                'chat_id' => $this->potatoUid,
                'text' => $this->potatoContactLastName.$this->potatoContactFirstName.'不是我们系统会员，不能执行该操作!',
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
        $callRecord->active_account = $this->callPersonData->account;
        $callRecord->unactive_account = $this->calledPersonData->account;
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
            return  $this->addError('bindCode','验证码为空');
        }
        $user = User::findOne(Yii::$app->user->id);
        if (!Yii::$app->redis->exists($this->bindCode)) {
            $this->addError('bindCode', '验证码错误!');
        } else {
            $potatoData = Yii::$app->redis->get($this->bindCode);
        }
        if (empty($potatoData)) {
            return $this->addError('bindCode', '验证码错误');
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
            return $this->addError('bindCode', '验证码错误!');
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