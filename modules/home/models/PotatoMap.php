<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;

class PotatoMap extends Model
{
    private $requestMapType = 1;
    private $potatoUid;
    private $searchMapText;
    private $webhookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $maphookurl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendLocation';
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


    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setSearchMapText($value)
    {
        $this->searchMapText = $value;
    }

    public function getSearchMapText()
    {
        return $this->searchMapText;
    }

    public function setSendData($value)
    {
        $this->sendData = $value;
    }

    public function getSendData()
    {
        return $this->sendData;
    }

    public function setWebhook($value)
    {
        $this->webhookUrl = $value;
    }

    public function getWebhook()
    {
        return $this->webhookUrl;
    }


    public function setMaphook($value)
    {
        $this->maphookurl = $value;
    }

    public function getMaphook()
    {
        return $this->maphookurl;
    }

    public function setPotatoUid($value)
    {
        $this->potatoUid = $value;
    }

    public function getPotatoUid()
    {
        return $this->potatoUid;
    }

    public function getRequestMapType()
    {
        return $this->requestMapType;
    }



    public function sendMap()
    {
        file_put_contents('/tmp/mypotato.log','patao--text '.$this->searchMapText.'---uid '.$this->potatoUid.PHP_EOL,8);
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            'latitude'=>212.03,
            'longitude'=>54.12,
        ];
        $this->sendPotatoData();
        return $this->errorCode['success'];
    }

    public function sendPotatoData($url = null)
    {
        if (empty($this->potatoUid)) {
            return "error #:";
        }
        if (is_array($this->sendData)) {
            $this->sendData = json_encode($this->sendData, true);
        }
        if (empty($url)) {
            $url = $this->getMaphook();
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