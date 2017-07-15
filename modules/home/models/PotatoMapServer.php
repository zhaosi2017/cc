<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;
use app\modules\home\models\PotatoMap;

class PotatoMapServer extends Model
{


    private $requestMapType = 6;
    private $requestLocationType = 5;
    private $requestTextType = 1;
    private $potatoUid;
    private $searchMapText;
    private $webHookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $mapHookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendLocation';
    private $venueHookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendVenue';
    private $sendData;
    private $maxRequestNum = 5;
    private $key;


    private $errorCode = [
        'success' => 200,
        'error' => 400,
        'invalid_operation' => 401,
        'not_yourself' => 402,
        'exist' => 403,
        'noexist' => 404,
        'emptyuid' => 405,
    ];


    public function setKey($value)
    {
        $this->key = $value;
    }

    public function getKey()
    {
        return $this->key;
    }
    public  function getRequestTextType()
    {
        return $this->requestTextType;
    }
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

    public function setWebHook($value)
    {
        $this->webHookUrl = $value;
    }

    public function getWebHook()
    {
        return $this->webHookUrl;
    }

    public function setVenueHook($value)
    {
        $this->venueHookUrl = $value;
    }

    public function getVenueHook()
    {
        return $this->venueHookUrl;
    }


    public function setMapHook($value)
    {
        $this->mapHookUrl = $value;
    }

    public function getMapHook()
    {
        return $this->mapHookUrl;
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

    public function getRequestLocationType()
    {
        return $this->requestLocationType;
    }

    public function sendMap()
    {
        $maps = $this->searchMap();

        if(!empty($maps)){
            foreach ($maps as $key=>$map)
            {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'latitude' => (float)$map->latitude,
                    'longitude' => (float)$map->longitude,
                    'title'=>$map->title,
                    'address'=>$map->address?$map->address:'',
                ];
                $this->sendPotatoData($this->venueHookUrl);
            }
        }
        return $this->errorCode['success'];
    }

    public function searchMap()
    {
        return  PotatoMap::find()->where(['like','title',$this->searchMapText])->limit($this->maxRequestNum)->all();
    }

    public function sendVenue()
    {
        file_put_contents('/tmp/rs.log',$this->searchMapText.PHP_EOL,8);
        return $this->errorCode['success'];

        $maps = $this->searchMap();
        if(!empty($maps)){
            foreach ($maps as $key=>$map)
            {
                $this->sendData = [
                    'chat_type' => 1,
                    'chat_id' => $this->potatoUid,
                    'latitude' => (double)$map->latitude,
                    'longitude' =>(double)$map->longitude,
                    'title'=>$map->title,
                    'address'=>$map->address?$map->address:'',
                ];
                $this->sendPotatoData($this->venueHookUrl);
            }
        }
        return $this->errorCode['success'];
    }

    public function addMap()
    {
        Yii::$app->redis->setex($this->key, 5*60, $this->searchMapText);
        return $this->errorCode['success'];
    }


    private function sendPotatoData($url = null)
    {
        if (empty($this->potatoUid)) {
            return "error #:";
        }
        if (is_array($this->sendData)) {
            $this->sendData = json_encode($this->sendData, true);
        }
        if (empty($url)) {
            $url = $this->getMapHook();
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