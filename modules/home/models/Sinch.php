<?php
/**
 * Created by PhpStorm.
 * User: nengliu
 * Date: 2017/7/13
 * Time: 下午2:49
 */

namespace app\modules\home\models;

use yii\base\Model;
use yii;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class Sinch extends Model
{

    private $auth_id = '893b8449-294a-4ee7-8f5f-0248d76588b7';
    private $auth_key = 'oV94O5CvBUClPO9x1EIg3Q==';

    private $body;          //提交数据
    private $authorization; //数据验证
    private $timestamp ;    //提交时间
    private $uri = 'https://callingapi.sinch.com/v1/callouts';

    private $telegramUrl = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
    private $potatoUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $_webhook;
    private $_sendData;
    private $_answerKey;
    private $_eventData;
    private $_tlanguage = 'zh-CN';
    private $_enventUrl;
    private $_answerUrl = 'https://www.callu.online/home/nexmo/conference?key=';
    private $_eventUrl = 'https://www.callu.online/home/nexmo/event';
    private $translateUrl = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk';
    private $loop = 3;
    private $cacheKeyPre = 'sinch_';
    private $callUrgentCallbackDataPre = 'cc_call_urgent';
    private $callCallbackDataPre = 'cc_call';
    private $failureStatus = ['ANSWERED', 'FAILED', 'NOANSWER', 'BUSY'];
    private $callUrgentText = 'Whether to call an emergency contact ?';
    private $callUrgentButtonText = 'Yes';
    private $againText = "Whether to call again ?";
    private $againButtonText = 'Re-call';


    /**
     * @param $value
     */
    public function setSendData($value)
    {
        $this->_sendData = $value;
    }

    /**
     * @param $value
     */
    public function setWebhook($value)
    {
        $this->_webhook = $value;
    }

    /**
     * @param $value
     */
    public function setAnswerKey($value)
    {
        $this->_answerKey = $value;
    }

    /**
     * @param $value
     */
    public function setEventData($value)
    {
        $this->_eventData = $value;
    }

    /**
     * @param $value
     */
    public function setTlanguage($value)
    {
        $this->_tlanguage = $value;
    }

    /**
     * @param $value
     */
    public function setEnventUrl($value)
    {
        $this->_enventUrl = $value;
    }

    /**
     * @return mixed
     */
    public function getSendData()
    {
        return $this->_sendData;
    }

    /**
     * @return mixed
     */
    public function getWebhook()
    {
        return $this->_webhook;
    }

    /**
     * @return mixed
     */
    public function getAnswerKey()
    {
        return $this->_answerKey;
    }

    /**
     * @return mixed
     */
    public function getEventData()
    {
        return $this->_eventData;
    }

    /**
     * @return mixed
     */
    public function getTlanguage()
    {
        return $this->_tlanguage;
    }

    /**
     * @return mixed
     */
    public function getAnswerUrl()
    {
        return $this->_answerUrl;
    }

    /**
     * @return mixed
     */
    public function getEnventUrl()
    {
        return $this->_eventUrl;
    }

    /**
     * @return string
     */
    public function getResultUrl()
    {
        return $this->_resultUrl;
    }

    /**
     * @return string
     */
    public function getCallUrgentText()
    {
        return Yii::t('app/model/nexmo', $this->callUrgentText, array(), $this->_tlanguage);
    }

    /**
     * @return string
     */
    public function getCallUrgentButtonText()
    {
        return Yii::t('app/model/nexmo', $this->callUrgentButtonText, array(), $this->_tlanguage);
    }

    /**
     * @return string
     */
    public function getAgainText()
    {
        return Yii::t('app/model/nexmo', $this->againText, array(), $this->_tlanguage);
    }

    /**
     * @return mixed
     */
    public function getAgainButtonText()
    {
        return Yii::t('app/model/nexmo', $this->againButtonText, array(), $this->_tlanguage);
    }

    /**
     * 呼叫.
     */
    public function callPerson($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $language, $appName, $appUid, $appCalledUid, $isFirst = 0, $calledNumberArr = array(), $calledUrgentArr = array(), $isUrgentMenu = 0)
    {
        $data = array(
            'status' => 0,
            'message' => '',
        );

        // 设置预言.
        $this->setTlanguage($language);
        if ($isFirst) {
            $number = UserPhone::find()->select(['id', 'phone_country_code', 'user_phone_number'])->where(['user_id' => $calledUserId])->orderBy('id asc')->all();
            $numberArr = [];
            if (!empty($number)) {
                foreach ($number as $key => $value) {
                    $numberArr[] = $value->phone_country_code . $value->user_phone_number;
                }
            }

            $urgent = UserGentContact::find()->select(['id', 'contact_country_code', 'contact_phone_number', 'contact_nickname'])->where(['user_id' => $calledUserId])->orderBy('id asc')->all();
            $urgentArr = [];
            if (!empty($urgent)) {
                foreach ($urgent as $key => $value) {
                    $tmp = [];
                    $tmp['phone_number'] = $value->contact_country_code . $value->contact_phone_number;
                    $tmp['nickname'] = $value->contact_nickname;
                    $urgentArr[] = $tmp;
                }
            }


            $calledNumberArr = $numberArr;
            $calledUrgentArr = $urgentArr;
        }

        // 呼叫人没有设置联系方式, 不能完成呼叫.
        if (empty($calledNumberArr) && empty($calledUrgentArr)) {
            $data['status'] = 1;
            return $data;
        }

        $failureStatus = false;
        if (!empty($calledNumberArr)) {
            $isUrgent = 0;
            $number = array_shift($calledNumberArr);
            $calledName = $calledAppName;
            $text = $isFirst ? $this->translateLanguage('正在呼叫'.$calledAppName.', 请稍候!') : $this->translateLanguage('正在尝试呼叫'.$calledAppName.'其他的联系电话, 请稍候!');
            $nexmoText = $callAppName.$this->translateLanguage(' 呼叫您上线'.$appName, '', 'en');
        } else {
            $isUrgent = 1;
            $urgentArr = array_shift($calledUrgentArr);
            if (empty($urgentArr)) {
                $failureStatus = true;
                $text = $this->translateLanguage('抱歉本次呼叫'.$calledAppName.'失败，请稍后再试, 或尝试其他方式联系'.$calledAppName.'!');
            } else {
                $number = $urgentArr['phone_number'];
                $text = $this->translateLanguage('正在呼叫' . $calledAppName . '的紧急联系人:' . $urgentArr['nickname'] . ', 请稍候!');
                $calledName = $urgentArr['nickname'];
                $nexmoText = $this->translateLanguage('请转告' . $calledAppName . ', 上线' . $appName, '', 'en');
            }
        }

        // 给机器人发消息提示操作.
        $this->sendMessageToRobot($appName, $appUid, $text);
        if ($failureStatus) {
            return $data;
        }
        $this->body = json_encode(
            ['method'=>'ttsCallout',
                "ttsCallout"=>[
                    "cli" => "46000000000",
                    "destination" =>[ "type" => "number", "endpoint" =>$number ],
                    "domain" => "pstn",
                    "custom" =>"customData",
                    "locale" => $this->_tlanguage,
                    "prompts" =>'#tts['.$nexmoText.'];myprerecordedfile',
                    'enabledice' => true,
                ],
            ]);
        $this->signature();
        $response = $this->_curl();
        $response  =json_decode($response);
        if(!empty($response) && isset($response->callId) && !empty($response->callId)){
            $uuid = $response->callId;
        }
        if (!empty($uuid)) {
            $cacheKey = $this->cacheKeyPre.$uuid;
            Yii::$app->redis->hset($cacheKey, 'number', $number);
            Yii::$app->redis->hset($cacheKey, 'appName', $appName);
            Yii::$app->redis->hset($cacheKey, 'language', $language);
            Yii::$app->redis->hset($cacheKey, 'appUid', $appUid);
            Yii::$app->redis->hset($cacheKey, 'appCalledUid', $appCalledUid);
            Yii::$app->redis->hset($cacheKey, 'isUrgent', $isUrgent);
            Yii::$app->redis->hset($cacheKey, 'isUrgentMenu', $isUrgentMenu);
            Yii::$app->redis->hset($cacheKey, 'calledUserId', $calledUserId);
            Yii::$app->redis->hset($cacheKey, 'callUserId', $callUserId);
            Yii::$app->redis->hset($cacheKey, 'calledAppName', $calledAppName);
            Yii::$app->redis->hset($cacheKey, 'calledName', $calledName);
            Yii::$app->redis->hset($cacheKey, 'callAppName', $callAppName);
            Yii::$app->redis->hset($cacheKey, 'calledNickname', $calledNickname);
            Yii::$app->redis->hset($cacheKey, 'callNickname', $callNickname);
            Yii::$app->redis->hset($cacheKey, 'contactPhoneNumber', $contactPhoneNumber);
            Yii::$app->redis->hset($cacheKey, 'calledNumberArr', json_encode($calledNumberArr));
            Yii::$app->redis->hset($cacheKey, 'calledUrgentArr', json_encode($calledUrgentArr));
            Yii::$app->redis->expire($cacheKey, 40*60);
        }
        return $uuid;
    }



    /**
     * 翻译语言.
     */
    private function translateLanguage($text, $source = null, $language = '')
    {

        $textArr = [
            "q" => $text,
            "format" => "text",
            "target" => (empty($language)) ? $this->getTlanguage() : $language,
        ];

        $data = $text;
        if (!empty($source)) {
            $textArr['source'] = $source;
        }
        $this->sendData = $textArr;
        $res = $this->sendRequest($this->translateUrl, true);
        $res = json_decode($res, true);

        if (isset($res['data']) && isset($res['data']['translations'])) {
            $data = $res['data']['translations'][0]['translatedText'];
        }

        return $data;
    }

    /**
     * 异步消息通知.
     */
    public function event()
    {

        $postData = $this->getEventData();
        $cacheKey = isset($postData['callid']) ? $postData['callid'] : '';
        $statusName = isset($postData['result']) ? $postData['result'] : '';
        if (empty($cacheKey)) {
            return false;
        } else {
            $cacheKey = $this->cacheKeyPre.$cacheKey;
        }

        // Yii::$app->redis->zincrby($cacheKey, 1, 'times');
        // $times = Yii::$app->redis->hget($cacheKey, 'times');
        if (isset($postData['result']) && $postData['result'] =='ANSWERED') {
            $status = 1;
        } elseif (in_array($statusName, $this->failureStatus)) {
            $status = 0;
        } else {
            return;
        }

        if (!empty($cacheKey)) {
            $conferenceCacheKey = Yii::$app->redis->hget($cacheKey, 'conferenceCacheKey');
            $calledUserId = Yii::$app->redis->hget($cacheKey, 'calledUserId');
            $callUserId = Yii::$app->redis->hget($cacheKey, 'callUserId');
            $calledName = Yii::$app->redis->hget($cacheKey, 'calledName');
            $calledAppName = Yii::$app->redis->hget($cacheKey, 'calledAppName');
            $callAppName = Yii::$app->redis->hget($cacheKey, 'callAppName');
            $calledNickname = Yii::$app->redis->hget($cacheKey, 'calledNickname');
            $callNickname = Yii::$app->redis->hget($cacheKey, 'callNickname');
            $contactPhoneNumber = Yii::$app->redis->hget($cacheKey, 'contactPhoneNumber');
            $calledNumberArr = Yii::$app->redis->hget($cacheKey, 'calledNumberArr');
            $calledUrgentArr = Yii::$app->redis->hget($cacheKey, 'calledUrgentArr');
            $number = Yii::$app->redis->hget($cacheKey, 'number');
            $isUrgent = Yii::$app->redis->hget($cacheKey, 'isUrgent');
            $isUrgentMenu = Yii::$app->redis->hget($cacheKey, 'isUrgentMenu');
            $language = Yii::$app->redis->hget($cacheKey, 'language');
            $appName = Yii::$app->redis->hget($cacheKey, 'appName');
            $appUid = Yii::$app->redis->hget($cacheKey, 'appUid');
            $appCalledUid = Yii::$app->redis->hget($cacheKey, 'appCalledUid');
            $calledNumberArr = json_decode($calledNumberArr, true);
            $calledUrgentArr = json_decode($calledUrgentArr, true);

            $this->setTlanguage($language);
            $appUid = intval($appUid);
            // 呼叫成功，产生费用.
            if ($status) {
                $text = $this->translateLanguage('呼叫'.$calledName.'成功!');
                Yii::$app->redis->del($cacheKey);
                Yii::$app->redis->del($conferenceCacheKey);
            } else {
                switch ($statusName) {
                    case 'BUSY':
                        $text = $this->translateLanguage('呼叫的用户忙!');
                        break;
                    case 'NOANSWER':
                        $text = $this->translateLanguage('呼叫'.$calledName.'失败, 暂时无人接听!');
                        break;
                    case 'FAILED':
                        $text = $this->translateLanguage('呼叫'.$calledName.'失败, 暂时无人接听!');
                        break;
                    default:
                        $text = $this->translateLanguage('呼叫'.$calledName.'失败!');
                        break;
                }

            }

            // 发消息到机器人.
            $this->sendMessageToRobot($appName, $appUid, $text);

            // 保存通话记录.
            $this->saveCallRecordData($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $number, $status, $isUrgent);

            // 如果是呼叫紧急联系人，需要推送按钮.
            if (empty($calledNumberArr) && !empty($calledUrgentArr) && empty($isUrgentMenu)) {
                switch ($appName) {
                    case 'telegram':
                        $callback = [
                            $this->callUrgentCallbackDataPre,
                            $appCalledUid,
                            $calledUserId,
                            $callAppName,
                            $calledAppName
                        ];
                        $text = $this->getCallUrgentText();
                        $keyBoard = [
                            [
                                [
                                    'text' => $this->getCallUrgentButtonText(),
                                    'callback_data' => implode('-', $callback),
                                ]
                            ]
                        ];
                        break;
                    case 'potato':
                        $callback = [
                            $this->callUrgentCallbackDataPre,
                            $appCalledUid,
                            $calledUserId,
                            $calledAppName
                        ];
                        $text = $this->getCallUrgentText();
                        $keyBoard = [
                            [
                                'type' => 0,
                                'text' => $this->getCallUrgentButtonText(),
                                'data' => implode('-', $callback),
                            ]
                        ];
                        break;
                    default :
                        break;
                }

                $this->sendMessageToRobot($appName, $appUid, $text, $keyBoard);
            } elseif (empty($calledNumberArr) && empty($calledUrgentArr) && empty($status)) {
                switch ($appName) {
                    case 'telegram':
                        $callback = [
                            $this->callCallbackDataPre,
                            $appCalledUid,
                            $callAppName,
                            $calledAppName
                        ];
                        $text = $this->getAgainText();
                        $keyBoard = [
                            [
                                [
                                    'text' => $this->getAgainButtonText(),
                                    'callback_data' => implode('-', $callback),
                                ]
                            ]
                        ];
                        break;
                    case 'potato':
                        $callback = [
                            $this->callCallbackDataPre,
                            $appCalledUid,
                            $calledUserId,
                            $calledAppName
                        ];
                        $text = $this->getAgainText();
                        $keyBoard = [
                            [
                                'type' => 0,
                                'text' => $this->getAgainButtonText(),
                                'data' => implode('-', $callback),
                            ]
                        ];
                        break;
                    default :
                        break;
                }
                $this->sendMessageToRobot($appName, $appUid, $text, $keyBoard);
            } else {
                // 呼叫失败, 呼叫下一联系人.
                if (!$status) {
                    $this->callPerson($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $language, $appName, $appUid, $appCalledUid,0, $calledNumberArr, $calledUrgentArr, $isUrgentMenu);
                }
            }
        }
    }

    /**
     * @param $appName
     * @param $appUid
     * @param $text
     */
    private function sendMessageToRobot($appName, $appUid, $text, $keyBoard = '')
    {
        switch ($appName) {
            case 'telegram':
                $this->setWebhook($this->telegramUrl);
                if (empty($keyBoard)) {
                    $this->sendData = [
                        'chat_id' => $appUid,
                        'text' => $text,
                    ];
                } else {
                    $this->sendData = [
                        'chat_id' => $appUid,
                        'text' => $text,
                        'reply_markup' => [
                            'inline_keyboard' => $keyBoard,
                        ],
                    ];
                }

                $this->sendRequest();
                break;
            case 'potato':
                $this->setWebhook($this->potatoUrl);
                if (empty($keyBoard)) {
                    $this->sendData = [
                        'chat_type' => 1,
                        'chat_id' => $appUid,
                        'text' => $text,
                    ];
                } else {
                    $this->sendData = [
                        'chat_type' => 1,
                        'chat_id' => $appUid,
                        'text' => $text,
                        'inline_markup' => $keyBoard,
                    ];
                }
                $this->sendRequest($this->webhook, true);
                break;
            default :
                break;
        }
    }

    /**
     * 保存通话记录.
     */
    private function saveCallRecordData($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $number, $status, $isUrgent)
    {
        $callRecord = new CallRecord();
        $callRecord->active_call_uid = $callUserId;
        $callRecord->unactive_call_uid = $calledUserId;
        $callRecord->active_account = $callAppName;
        $callRecord->unactive_account = $calledAppName;
        $callRecord->active_nickname = $callNickname;
        $callRecord->unactive_nickname = $calledNickname;
        $callRecord->contact_number = $contactPhoneNumber;
        $callRecord->unactive_contact_number = $number;

        $callRecord->status = $status ? 0 : 1;
        $callRecord->call_time = time();
        $callRecord->type = ($isUrgent) ? 1 : 0;
        $res = $callRecord->save();

        return $res ? true : false;
    }

    /**
     * 发送菜单.
     *
     * @return json.
     */
    public function sendRequest($url = null, $escape = false)
    {
        if (is_array($this->sendData)) {
            $this->sendData = $escape ? json_encode($this->sendData,JSON_UNESCAPED_UNICODE) : json_encode($this->sendData, true);
        }
        if (empty($url)) {
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


    /**
     * 数据签名
     */
    private function signature(){
        $this->timestamp = date("c");
        $path                  = "/v1/callouts";
        $content_type          = "application/json";
        $canonicalized_headers = "x-timestamp:" . $this->timestamp;

        $content_md5 = base64_encode( md5( utf8_encode($this->body), true ));
        $string_to_sign =
            "POST\n".
            $content_md5."\n".
            $content_type."\n".
            $canonicalized_headers."\n".
            $path;
        $signature = base64_encode(hash_hmac("sha256", utf8_encode($string_to_sign), base64_decode($this->auth_key), true));
        $this->authorization = "Application " . $this->auth_id . ":" . $signature;
    }

    /**
     * 发送一个消息
     */
    private function _curl(){

        $curl = curl_init($this->uri);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [ 'content-type: '."application/json",
                'x-timestamp:' . $this->timestamp,
                'authorization:' . $this->authorization]
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl , CURLOPT_TIMEOUT, 20);
        try{
            $curl_response = curl_exec($curl);

        }catch (Exception $e){
            $this->error('Curl error: '. curl_error($curl));
        }
        curl_close($curl);

        return $curl_response;
    }
}