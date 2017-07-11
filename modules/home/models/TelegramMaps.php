<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;
use app\modules\home\models\WhiteList;
use app\modules\home\models\BlackList;
use app\modules\home\models\UserPhone;

class TelegramMaps extends Model
{



    public $keyboard;

    public $bindCode;
    public $telegramUid;
    public $sendData;

    private $uri;
    public $user_latitude;
    public $user_longitude;

    private $loactions = [
        1 =>['latitude'=>11.544087,
            'longitude'=>104.921573,
            'title'=>'毛泽东大道加油站',
            'address' =>'Mao Tse Toung Boulevard (245)',],
        2=>['latitude'=>11.544082,
            'longitude'=>104.921583,
            'title'=>'莫呢网大道加油站',
            'address' =>'Mao Tse Toung Boulevard (378）',],
        3=>['latitude'=>11.541087,
            'longitude'=>104.921673,
            'title'=>'永旺加油站',
            'address' =>'Mao Tse Toung Boulevard (745)',],
        4=>['latitude'=>11.541077,
            'longitude'=>104.921693,
            'title'=>'卢旺达加油站',
            'address' =>'rouess Tse Toung Boulevard (745)',],
        5=>['latitude'=>11.541047,
            'longitude'=>104.921693,
            'title'=>'卢旺达加油站',
            'address' =>'rouess Tse Toung Boulevard (945)',],
        6=>['latitude'=>11.541075,
            'longitude'=>104.921693,
            'title'=>'安哥拉斯加油站',
            'address' =>'makati Tse Toung Boulevard (735)',],
    ];



    /**
     * 设置webhook
     */
    public function setWebhook()
    {
        $this->uri = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendLocation';
    }

    /**
     * @return json
     * 定位一下用户的具体位置
     *
     */
    public function sendLocation(){
        $this->sendData = array(
                'chat_id'=>$this->telegramUid,
                'latitude'=>11.544086,
                'longitude'=>104.921572,
                'disable_notification'=>'',
                'reply_to_message_id'=>'',
                'reply_markup'=>''
        );
        $res =  $this->sendTelegramData();
        if($res->ok){
            $this->user_latitude = $res->result->location->latitude;
            $this->user_longitude = $res->result->location->longitude;
        }
        return $res->ok;
    }


    public function sendVenue($number = 1){
        $this->uri = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendVenue';
        for($i= 1 ; $i<=$number; $i++){
            $this->sendData = $this->loactions[$i];
            $this->sendData['chat_id'] = $this->telegramUid;
            $this->sendData['foursquare_id']='';
            $this->sendData['disable_notification']='';
            $this->sendData['reply_to_message_id']='';
            $this->sendData['reply_markup']='';
            $this->sendTelegramData();
        }
    }



    /**
     * 发送菜单.
     *
     * @return json.
     */
    public function sendTelegramData( $escape = false)
    {
        if (is_array($this->sendData)) {
            $this->sendData = $escape ? json_encode($this->sendData,JSON_UNESCAPED_UNICODE) : json_encode($this->sendData, true);
        }
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL =>  $this->uri,
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

        $response = json_decode($response);
        return $response;

    }

}