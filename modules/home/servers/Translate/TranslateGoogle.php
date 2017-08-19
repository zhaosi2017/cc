<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/10
 * Time: 上午10:15
 */

namespace app\modules\home\servers\Translate;

class TranslateGoogle extends  TranslateAbstract {

    private $uri = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyAV_rXQu5ObaA9_rI7iqL4EDB67oXaH3zk';
    private $Language = [
        'zh-CN' =>'zh',
        'en-US' =>'en',
        'zh-TW'=>'zh-TW',
    ];

    /**
     * 翻译语言.
     */
    public function Translate(Array $option = [])
    {

        $textArr = [
            "q" => $this->text,
            "format" => "text",
            "target" => $this->getLanguage($this->deration_Language),
        ];
        if(!empty($this->Source_Language)){

            $textArr['source'] = $this->getLanguage($this->Source_Language);
        }
        $res = $this->post($textArr);
        $res = json_decode($res, true);

        if (isset($res['data']) && isset($res['data']['translations'])) {
            $data = $res['data']['translations'][0]['translatedText'];
        }
        return $data;
    }


    private function getLanguage($Language){
        if(key_exists($Language , $this->Language)){
            return $this->Language[$Language];
        }
        $temp =   explode('-' , $Language);
        $Language = $temp[0];
        return $Language;
    }

    private function post($data){

        $header = ['cache-control'=>'no-cache' , 'content-type'=>'application/json'];

        $body = json_encode($data, JSON_UNESCAPED_UNICODE);
        $client = new \GuzzleHttp\Client();
        $request  = new \GuzzleHttp\Psr7\Request('POST' , $this->uri , $header , $body);
        $response = $client->send($request,['timeout' => 3]);
        if($response->getStatusCode() !== 200){
            return false;
        }
        return $response->getBody();
    }
}