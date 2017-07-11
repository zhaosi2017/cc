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



    /**
     * 设置webhook
     */
    public function setWebhook()
    {
        $this->uri = 'https://api.telegram.org/bot366429273:AAE1lGFanLGpUbfV28zlDYSTibiAPLhhE3s/sendLocation';
    }

    public function sendLocation(){
        $this->sendData = array(
                'chat_id'=>$this->telegram_user_id,
                'latitude'=>11.544086,
                'longitude'=>104.921572,
                'disable_notification'=>'',
                'reply_to_message_id'=>'',
                'reply_markup'=>''
        );
        return $this->sendTelegramData();
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
        return $response->ok;

    }

}