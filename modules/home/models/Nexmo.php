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
    private $applicationId = '570db7b5-09cb-45b3-a097-e0b8e0bcec65';
    private $telegramUrl = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendMessage';
    private $potatoUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $_webhook;
    private $_sendData;
    private $_answerKey;
    private $_eventData;
    private $_tlanguage;
    private $_enventUrl;
    private $_answerUrl = 'https://www.callu.online/home/nexmo/conference?key=';
    private $_eventUrl = 'https://www.callu.online/home/nexmo/event';
    private $translateUrl = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk';
    private $_resultUrl = 'https://api.nexmo.com/v1/calls';
    private $loop = 3;
    private $voice = 'male';


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
     * 呼叫.
     */
    public function callPerson($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $language, $appName, $appUid, $isFirst = 0, $calledNumberArr = array(), $calledUrgentArr = array())
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

        $basic = new \Nexmo\Client\Credentials\Basic($this->apiKey, $this->apiScret);
        $privatePath = Yii::getAlias('@app').'/config/'.'private.key';
        $keypair = new \Nexmo\Client\Credentials\Keypair(file_get_contents($privatePath), $this->applicationId);
        $client = new \Nexmo\Client(new \Nexmo\Client\Credentials\Container($basic, $keypair));

        $failureStatus = false;
        if (!empty($calledNumberArr)) {
            $isUrgent = 0;
            $number = array_shift($calledNumberArr);
            $calledName = $calledAppName;
            $nexmoText = $callAppName.$this->translateLanguage('呼叫您上线'.$appName);
            $text = $isFirst ? $this->translateLanguage('正在呼叫'.$calledAppName.', 请稍后!') : $this->translateLanguage('正在尝试呼叫'.$calledAppName.'的另外联系电话, 请稍后!');
        } else {
            $isUrgent = 1;
            $urgentArr = array_shift($calledUrgentArr);
            if (empty($urgentArr)) {
                $failureStatus = true;
                $text = $this->translateLanguage('抱歉本次呼叫'.$calledAppName.'失败，请稍后再试, 或尝试其他方式联系'.$calledAppName.'!');
            } else {
                $number = $urgentArr['phone_number'];
                $nexmoText = $this->translateLanguage('请转告' . $calledAppName . ', 上线' . $appName);
                $text = $this->translateLanguage('正在呼叫' . $calledAppName . '的紧急联系人:' . $urgentArr['nickname'] . ', 请稍后!');
                $calledName = $urgentArr['nickname'];
            }
        }

        // 给机器人发消息提示操作.
        $this->sendMessageToRobot($appName, $appUid, $text);
        if ($failureStatus) {
            return $data;
        }
        $cacheKey = $callUserId.time();
        $conference = [
            'action' => 'talk',
            'loop' => $this->loop,
            'lg' => $this->getTlanguage(),
            'voiceName' => $this->voice,
            'text' => $nexmoText,
        ];

        $conferenceCacheKey = $cacheKey.'_pre';
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
            $cacheKey = $uuid;
            Yii::$app->redis->hset($cacheKey, 'time', time());
            Yii::$app->redis->hset($cacheKey, 'number', $number);
            Yii::$app->redis->hset($cacheKey, 'appName', $appName);
            Yii::$app->redis->hset($cacheKey, 'language', $language);
            Yii::$app->redis->hset($cacheKey, 'appUid', $appUid);
            Yii::$app->redis->hset($cacheKey, 'isUrgent', $isUrgent);
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
            Yii::$app->redis->expire($cacheKey, 5*60);
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
        $cacheData = Yii::$app->redis->get($this->getAnswerKey());
        if (!empty($cacheData)) {
            $tmp = json_decode($cacheData, true);
            $tmp = json_encode($tmp, JSON_UNESCAPED_UNICODE);
            $data = [
                $tmp
            ];
        } else {
            $data = [];
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
    private function translateLanguage($text, $source = null)
    {

        $textArr = [
            "q" => $text,
            "format" => "text",
            "target" => $this->getTlanguage(),
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
        $cacheKey = isset($postData['uuid']) ? $postData['uuid'] : '';
        if (empty($cacheKey)) {
            return false;
        }

        // Yii::$app->redis->zincrby($cacheKey, 1, 'times');
        // $times = Yii::$app->redis->hget($cacheKey, 'times');
        if (isset($postData['duration'])) {
            $postData['duration'] > 0 ? ($status = 1) : ($status = 0);
        } else {
            return;
        }

        if (!empty($cacheKey)) {
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
            $language = Yii::$app->redis->hget($cacheKey, 'language');
            $appName = Yii::$app->redis->hget($cacheKey, 'appName');
            $appUid = Yii::$app->redis->hget($cacheKey, 'appUid');
            $calledNumberArr = json_decode($calledNumberArr, true);
            $calledUrgentArr = json_decode($calledUrgentArr, true);

            // 呼叫成功，产生费用.
            if ($status) {
                $text = $this->translateLanguage('呼叫'.$calledName.'成功!');
            } else {
                $text = $this->translateLanguage('呼叫'.$calledName.'失败!');
            }

            // 发消息到机器人.
            $this->sendMessageToRobot($appName, $appUid, $text);

            // 保存通话记录.
            $this->saveCallRecordData($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $number, $status, $isUrgent);

            // 呼叫失败, 呼叫下一联系人.
            if (!$status) {
                $this->callPerson($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $language, $appName, $appUid,0, $calledNumberArr, $calledUrgentArr);
            }
        }
    }

    /**
     * @param $appName
     * @param $appUid
     * @param $text
     */
    private function sendMessageToRobot($appName, $appUid, $text)
    {
        switch ($appName) {
            case 'telegram':
                $this->setWebhook($this->telegramUrl);
                $this->sendData = [
                    'chat_id' => $appUid,
                    'text' => $text,
                ];
                $this->sendRequest();
                break;
            case 'potato':
                $this->setWebhook($this->potatoUrl);
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $appUid,
                    'text' => $text,
                ];
                $this->sendRequest();
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

}