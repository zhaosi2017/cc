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
    private $searchText;
    private $webHookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
    private $mapHookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendLocation';
    private $venueHookUrl = 'http://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendVenue';
    private $sendData;
    private $maxRequestNum = 5;
    private $key;
    private $searchData ;
    private $message;


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


    public function setMessage($value)
    {
        $this->message = $value;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setSearchData( array $value)
    {
        $this->searchData = $value;
    }

    public function getSearchData()
    {
        return $this->searchData;
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

    public function setSearchText($value)
    {
        $this->searchText = $value;
    }

    public function getSearchText()
    {
        return $this->searchText;
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

    public function searchMap()
    {
        return PotatoMap::find()->where(['like','title',$this->searchText])->limit($this->maxRequestNum)->all();
    }

    public function sendVenue()
    {
        $messages = json_decode($this->searchMapText,true);
        $message = $messages['text'];

        if(preg_match('/^\/map/i',$message)){
            $arr = explode(' ',$message);
            if(isset($arr[1]) && !empty($arr[1])) {
                $this->searchText = $arr[1];
                $this->sendMap();
                return $this->errorCode['success'];
            }
        }

        $this->searchText = $message;
        if($this->userAddMap()){
            $this->message = '恭喜您发布成功！';
            $this->sendMessage();
        }
        return $this->errorCode['success'];

    }


    private function sendMessage()
    {
        $this->sendData = [
            'chat_type' => 1,
            'chat_id' => $this->potatoUid,
            "text"=> $this->message
        ];
        $this->sendPotatoData($this->webHookUrl);
    }

    private function  userAddMap()
    {
        $content = Yii::$app->redis->get($this->key);
        file_put_contents('/tmp/r.log',$this->potatoUid.'content'.$content.PHP_EOL,8);
        if($content){
            file_put_contents('/tmp/r.log','---useraddmap--2 ---'.PHP_EOL,8);
            $contents = json_decode($content,true);
            $potatoMap = new PotatoMap();
            $potatoMap->chat_id = $this->potatoUid;
            $potatoMap->title = $this->searchText;
            $potatoMap->address = $this->searchText;
            $potatoMap->description = $this->searchText;
            $potatoMap->latitude = $contents['latitude'];
            $potatoMap->longitude = $contents['longitude'];
             if($potatoMap->save())
             {
                 Yii::$app->redis->del($this->key);
                 return true;
             }
        }
        return false;
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