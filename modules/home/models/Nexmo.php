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

class Nexmo extends Model
{

    private $apiKey = '85704df7';
    private $apiScret = '755026fdd40f34c2';
    private $applicationId = '454eb4c4-1fdd-4b4b-9423-937c80f01bb8';
    private $telegramUrl = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
    private $potatoMenuWebHookUrl = 'https://bot.potato.im:5423/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendInlineMarkupMessage';
    private $potatoUrl = 'https://bot.potato.im:5423/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $_webhook;
    private $_sendData;
    private $_answerKey;
    private $_eventData;
    private $_tlanguage = 'zh-CN';
    private $_language = 'zh-CN';
    private $_enventUrl;
    private $_answerUrl = 'https://www.callu.online/home/nexmo/conference?key=';
    private $_eventUrl = 'https://www.callu.online/home/nexmo/event';
    private $translateUrl = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk';
    private $_resultUrl = 'https://api.nexmo.com/v1/calls';
    private $loop = 3;
    private $voice = 'Joey';
    private $cacheKeyPre = 'nexmo_';
    private $callUrgentCallbackDataPre = 'cc_call_urgent';
    private $callCallbackDataPre = 'cc_call';
    private $callUrgentButtonText = 'Yes';
    private $againButtonText = 'Re-call';
    private $failureStatus = ['unanswered', 'busy', 'timeout', 'failed', 'rejected'];
    private $successStatus = ['answered'];
    private $completeStatus = ['completed'];
    private $callUrgentText = 'Whether to call an emergency contact ?';
    private $againText = "Whether to call again ?";
    private $failureText = "Call failed, please try again later!";
    private $firstFailureText = "Called user does not set contact phone, call failed!";


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
        if (!stripos($value, '-')) {
            switch ($value) {
                case 'zh';
                    $this->_language = 'zh-CN';
                default;
                    break;
            }
        } else {
            $this->_language = $value;
        }

        // tlanguage语言设置.
        if (!stripos($value, 'zh')) {
            $language = explode('-', $value);
            $this->_tlanguage = $language[0];
        } else {
            $this->_tlanguage = $value;
        }
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
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @return string
     */
    public function getCallUrgentText()
    {
        return Yii::t('app/model/nexmo', $this->callUrgentText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getCallUrgentButtonText()
    {
        return Yii::t('app/model/nexmo', $this->callUrgentButtonText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getAgainText()
    {
        return Yii::t('app/model/nexmo', $this->againText, array(), $this->language);
    }

    /**
     * @return mixed
     */
    public function getAgainButtonText()
    {
        return Yii::t('app/model/nexmo', $this->againButtonText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getFailureText()
    {
        return Yii::t('app/model/nexmo', $this->failureText, array(), $this->language);
    }

    /**
     * @return string
     */
    public function getFirstFailureText()
    {
        return Yii::t('app/model/nexmo', $this->firstFailureText, array(), $this->language);
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
            $isFirst ? ($text = $this->getFirstFailureText()) : ($text = $this->getFailureText());
            // 发消息到机器人.
            $res = $this->sendMessageToRobot($appName, $appUid, $text);
            return $res;
        }

        // 第一次呼叫，用户没有设置紧急联系人, 推送是否发送紧急联系人按钮.
        if ($isFirst && empty($calledNumberArr) && !empty($calledUrgentArr)) {
            $res = $this->sendUrgentButtonToRobot($appName, $appCalledUid, $appUid, $calledAppName, $callAppName, $calledUserId);
            return $res;
        }

        $basic = new \Nexmo\Client\Credentials\Basic($this->apiKey, $this->apiScret);
        $privatePath = Yii::getAlias('@app').'/config/'.'private.key';
        $keypair = new \Nexmo\Client\Credentials\Keypair(file_get_contents($privatePath), $this->applicationId);
        $client = new \Nexmo\Client(new \Nexmo\Client\Credentials\Container($basic, $keypair));

        $failureStatus = false;
        if (!empty($calledNumberArr)) {
            $isUrgent = 0;
            $number = array_shift($calledNumberArr);
            $calledName = $calledAppName;
            $text = $isFirst ? $this->translateLanguage('正在呼叫'.$calledAppName.', 请稍候!') : $this->translateLanguage('正在尝试呼叫'.$calledAppName.'其他的联系电话, 请稍候!');
            $nexmoText = $callAppName.' 呼叫您上线'.$appName;
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
                $nexmoText = '请转告' . $calledAppName . ', 上线' . $appName;
            }
        }

        $file = 'ntext_'.date('Y-m-d', time()).'.txt';
        file_put_contents('/tmp/'.$file, var_export($nexmoText, true).PHP_EOL, 8);

        // 给机器人发消息提示操作.
        $this->sendMessageToRobot($appName, $appUid, $text);
        if ($failureStatus) {
            return $data;
        }

        $cacheKey = $callUserId.time();
        $nexmoText = $this->translateLanguage($nexmoText, '', 'en');
        $tmp = [
            'action' => 'talk',
            'loop' => $this->loop,
            // 'lg' => $this->getTlanguage(),
            'voiceName' => $this->voice,
            'text' => $nexmoText,
        ];

        $conference = [
            $tmp,
        ];
        $conferenceCacheKey = $cacheKey.'_pre';
        $file = 'nexmo_'.date('Y-m-d', time()).'.txt';
        file_put_contents('/tmp/'.$file, var_export($conferenceCacheKey, true).PHP_EOL, 8);
        file_put_contents('/tmp/'.$file, var_export($cacheKey, true).PHP_EOL, 8);
        file_put_contents('/tmp/'.$file, var_export($conference, true).PHP_EOL, 8);
        Yii::$app->redis->set($conferenceCacheKey, json_encode($conference, JSON_UNESCAPED_UNICODE));
        Yii::$app->redis->expire($conferenceCacheKey, 5*60);
        $anserUrl = $this->getAnswerUrl();
        $eventUrl = $this->getEnventUrl();
        $call = $client->calls()->create([
            'to' => [[
                'type' => 'phone',
                'number' => $number
            ]],
            'from' => [
                'type' => 'phone',
                'number' => $contactPhoneNumber
            ],
            'answer_url' => [
                $anserUrl.$conferenceCacheKey,
            ],
            'event_url' => [
                $eventUrl,
            ]
        ]);

        $call = json_encode($call, JSON_UNESCAPED_UNICODE);
        $call = json_decode($call, true);
        $uuid = isset($call['uuid']) ? $call['uuid'] : '';

        if (!empty($uuid)) {
            $cacheKey = $this->cacheKeyPre.$uuid;
            Yii::$app->redis->hset($cacheKey, 'conferenceCacheKey', $conferenceCacheKey);
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
     * 电话语音内容.
     *
     * @return array|mixed|object
     */
    public function answer()
    {
        $cacheKey = $this->getAnswerKey();
        $cacheData = Yii::$app->redis->get($cacheKey);
        if (!empty($cacheData)) {
            $data = $cacheData;
            Yii::$app->redis->del($cacheKey);
        } else {
            $data = '[]';
        }

        return $data;
    }

    /**
     * @param $application_id
     * @param $keyfile
     * @return \Lcobucci\JWT\Token
     */
    private function generate_jwt($application_id, $keyfile) {

        date_default_timezone_set('UTC');               //Set the time for UTC + 0
        $key = file_get_contents($keyfile);                             //Retrieve your private key
        $signer = new Sha256();
        $privateKey = new Key($key);

        $jwt = (new Builder())->setIssuedAt(time() - date('Z'))  // Time token was generated in UTC+0
                ->set('application_id', $application_id)                // ID for the application you are working with
                ->setId( base64_encode( mt_rand (  )), true)
                ->sign($signer,  $privateKey)                           // Create a signature using your private key
                ->getToken();                                           // Retrieves the JWT

        return $jwt;
    }

    /**
     * 主动获取通话结果.
     */
    public function getCallResult($uuid)
    {
        $base_url = $this->getResultUrl();
        $url = $base_url.'/'.$uuid;
        $application_id = $this->applicationId;
        $privatePath = Yii::getAlias('@app').'/config/'.'private.key';
        $jwt = $this->generate_jwt($application_id, $privatePath);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer " . $jwt ));
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        return $response;
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

        if (isset($res['data']) && isset($res['data']['translations'])) {
            $data = $res['data']['translations'][0]['translatedText'];
        }

        return $data;
    }

    /**
     * 异步消息通知.
     *
     * started - 平台盯着电话.
     * ringing - 用户的手机响了.
     * answered - 用户已经接听了您的来电.
     * machine - 平台检测到应答机.
     * complete - 平台已终止此呼叫.
     * timeout- 您的用户ringing_timer几秒钟内没有接听电话.
     * failed - 呼叫未能完成.
     * rejected - 电话被拒绝.
     * unanswered - 电话没有回答.
     * busy - 被叫的人正在接通电.
     *
     * return mixed.
     */
    public function event()
    {

        $postData = $this->getEventData();
        $time = time();
        $file = 'event_'.date('Y-m-d', $time).'.txt';
        file_put_contents('/tmp/'.$file, var_export($postData, true).PHP_EOL, 8);
        $cacheKey = isset($postData['uuid']) ? $postData['uuid'] : '';
        $statusName = isset($postData['status']) ? $postData['status'] : '';
        if (empty($cacheKey)) {
            return false;
        } else {
            $file = 'eventt_'.date('Y-m-d', $time).'.txt';
            file_put_contents('/tmp/'.$file, var_export($postData, true).PHP_EOL, 8);
            $cacheKey = $this->cacheKeyPre.$cacheKey;
        }

        // $isSend = Yii::$app->redis->hget($cacheKey, 'isSend');
        if (isset($postData['duration']) && $postData['duration'] > 0) {
            $status = 1;
        } elseif (in_array($statusName, $this->failureStatus)) {
            // Yii::$app->redis->hset($cacheKey, 'isSend', 1);
            $status = 0;
        } elseif(in_array($statusName, $this->successStatus)) {
            $status = 1;
        // } elseif(empty($isSend) && isset($postData['duration']) && (in_array($postData['status'], $this->completeStatus))) {
        //     $status = 0;
        } else {
            // 返回无效的状态，比如started,ringing等状态.
            return false;
        }

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
        $isUrgentMenu = intval($isUrgentMenu);

        // 呼叫成功，产生费用.
        if ($status) {
            Yii::$app->redis->del($cacheKey);
            // Yii::$app->redis->del($conferenceCacheKey);
            $text = $this->translateLanguage('呼叫'.$calledName.'成功!');
        } else {
            switch ($statusName) {
                case 'busy':
                    $text = $this->translateLanguage('呼叫的用户忙!');
                    break;
                case 'timeout':
                    $text = $this->translateLanguage('呼叫'.$calledName.'失败, 暂时无人接听!');
                    break;
                case 'unanswered':
                    $text = $this->translateLanguage('呼叫'.$calledName.'失败, 暂时无人接听!');
                    break;
                case 'failed':
                    $text = $this->translateLanguage('呼叫'.$calledName.'失败, 请稍后再试!');
                    break;
                case 'rejected':
                    $text = $this->translateLanguage('呼叫'.$calledName.'失败, 被拒绝!');
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

        if (empty($calledNumberArr) && !empty($calledUrgentArr) && empty($isUrgentMenu) && empty($status)) {
            // 如果是呼叫紧急联系人，需要推送按钮.
            $res = $this->sendUrgentButtonToRobot($appName, $appCalledUid, $appUid, $calledAppName, $callAppName, $calledUserId);
            Yii::$app->redis->del($cacheKey);
            Yii::$app->redis->del($conferenceCacheKey);
        } elseif (empty($calledNumberArr) && empty($calledUrgentArr) && empty($status)) {
            // 推送重拨按钮.
            $res = $this->sendRecallButtonToRobot($appName, $appCalledUid, $appUid, $calledAppName, $callAppName);
            Yii::$app->redis->del($cacheKey);
            Yii::$app->redis->del($conferenceCacheKey);
        } elseif (empty($status)) {
            // 呼叫失败, 呼叫下一联系人.
            $res = $this->callPerson($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $language, $appName, $appUid, $appCalledUid,0, $calledNumberArr, $calledUrgentArr, $isUrgentMenu);
        }

        return $res;
    }


    /**
     * 发送是否是否发送紧急联系人的按钮.
     *
     * @param $appName
     * @param $appCalledUid
     * @param $appUid
     * @param $calledAppName
     * @param $callAppName
     * @param $calledUserId
     *
     */
    private function sendUrgentButtonToRobot($appName, $appCalledUid, $appUid, $calledAppName, $callAppName, $calledUserId)
    {
        $time = time();
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
                    $callAppName,
                    $calledAppName,
                    $time
                ];
                $text = $this->getCallUrgentText();
                $keyBoard = [
                    [
                        [
                            'type' => 0,
                            'text' => $this->getCallUrgentButtonText(),
                            'data' => implode('-', $callback),
                        ]
                    ]
                ];
                break;
            default :
                break;
        }

        $result  = $this->sendMessageToRobot($appName, $appUid, $text, $keyBoard);

        return $result;
    }

    /**
     * 是否推送重新呼叫按钮.
     *
     * @param $appName
     * @param $appCalledUid
     * @param $appUid
     * @param $calledAppName
     * @param $callAppName
     */
    public function sendRecallButtonToRobot($appName, $appCalledUid, $appUid, $calledAppName, $callAppName)
    {
        $time = time();
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
                    $callAppName,
                    $calledAppName,
                    $time
                ];
                $text = $this->getAgainText();
                $keyBoard = [
                    [
                        [
                            'type' => 0,
                            'text' => $this->getAgainButtonText(),
                            'data' => implode('-', $callback),
                        ]
                    ]
                ];
                break;
            default :
                break;
        }

        $result = $this->sendMessageToRobot($appName, $appUid, $text, $keyBoard);

        return $result;
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
                    $this->setWebhook($this->potatoMenuWebHookUrl);
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

        if ($err) {
            $file = 'curl_error_'.date('Y-m-d', time()).'.txt';
            file_put_contents('/tmp/'.$file, var_export($err, true).PHP_EOL, 8);
            return "error #:" . $err;
        } else {
            if (!is_array($response)) {
                $response = json_decode($response, true);
            }
            $file = 'curl_response_'.date('Y-m-d', time()).'.txt';
            file_put_contents('/tmp/'.$file, var_export($response, true).PHP_EOL, 8);
            return $response;
        }

    }

}