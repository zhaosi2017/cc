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

class Nexmo extends Model
{

    private $apiKey = '85704df7';
    private $apiScret = '755026fdd40f34c2';
    private $applicationId = '570db7b5-09cb-45b3-a097-e0b8e0bcec65';
    private $_webhook;
    private $_sendData;
    private $_answerUrl = '';
    private $_eventUrl = '';
    private $loop = 3;
    private $voice = 'male';


    /**
     * @param $value
     */
    public function setSendData($value)
    {
        $this->_sendData = $value;
    }

    public function setWebhook($value)
    {
        $this->_webhook = $value;
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
     * 呼叫.
     */
    public function callPerson($calledUserId, $callUserId, $calledAppName, $callAppName, $calledNickname, $callNickname, $contactPhoneNumber, $isFirst = 0, $calledNumberArr = array(), $calledUrgentArr = array())
    {
        $data = array(
            'status' => 0,
            'message' => '',
        );

        if (empty($calledNumberArr) && $isFirst) {
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
                    $tmp['phone_number'] = $value->contact_country_code . $this->contact_phone_number;
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
        if (!empty($calledNumberArr)) {
            $number = array_shift($calledNumberArr);
        } else {
            $number = array_shift($calledUrgentArr);
        }

        $text = '';
        $cacheKey = $callUserId.time();
        $tmp = [
            'action' => 'talk',
            'loop' => $this->loop,
            'voice' => $this->voice,
            'text' => $text,
        ];

        $conference = [
            json_encode($tmp, JSON_UNESCAPED_UNICODE),
        ];
        $conferenceCacheKey = $cacheKey.'_pre';
        Yii::$app->redis->expire($conferenceCacheKey, json_encode($conference, JSON_UNESCAPED_UNICODE));
        Yii::$app->redis->expire($conferenceCacheKey, 10*60);
        $call = $client->calls()->create([
            'to' => [[
                'type' => 'phone',
                'number' => $number
            ]],
            'from' => [
                'type' => 'phone',
                'number' => $contactPhoneNumber
            ],
            'answer_url' => ['https://www.callu.online/home/nexmo/conference?key='.$conferenceCacheKey],
            'event_url' => ['https://www.callu.online/home/nexmo/event?key='.$cacheKey,]
        ]);

        $call = json_encode($call, JSON_UNESCAPED_UNICODE);
        $call = json_decode($call, true);
        $uuid = isset($call['uuid']) ? $call['uuid'] : '';

        if (!empty($uuid)) {
            Yii::$app->redis->hset($cacheKey, 'time', time());
            Yii::$app->redis->hset($cacheKey, 'uuid', $uuid);
            Yii::$app->redis->hset($cacheKey, 'calledUserId', $calledUserId);
            Yii::$app->redis->hset($cacheKey, 'callUserId', $callUserId);
            Yii::$app->redis->hset($cacheKey, 'calledAppName', $calledAppName);
            Yii::$app->redis->hset($cacheKey, 'callAppName', $callAppName);
            Yii::$app->redis->hset($cacheKey, 'calledNickname', $calledNickname);
            Yii::$app->redis->hset($cacheKey, 'callNickname', $callNickname);
            Yii::$app->redis->hset($cacheKey, 'contactPhoneNumber', $contactPhoneNumber);
            Yii::$app->redis->hset($cacheKey, 'calledNumberArr', json_encode($calledNumberArr));
            Yii::$app->redis->hset($cacheKey, 'calledUrgentArr', json_encode($calledUrgentArr));
            Yii::$app->redis->expire($cacheKey, 30*60);
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
        $cachKey = Yii::$app->request->get('key');
        $data = Yii::$app->redis->get($cachKey);
        if (!empty($data)) {
            $data = json_decode($data, true);
        } else {
            $data = [];
        }

        return $data;
    }

    /**
     * 异步消息通知.
     */
    public function event()
    {

    }

    /**
     * 发送菜单.
     *
     * @return json.
     */
    private function sendRequest($url = null, $escape = false)
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