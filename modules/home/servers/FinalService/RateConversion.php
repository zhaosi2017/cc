<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/19
 * Time: 上午9:58
 * 汇率转换服务
 */
namespace app\modules\home\servers\FinalService;
class RateConversion{



    public $target;  //目标币种
    public $source;  //源币种

    private $maps = [
        'CNY',
        'USD',
        'EUR'
    ];

    public function conversion(){

      $name = $this->source.$this->target;
      $url = 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22'.$name.'%22)&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
      try{
          $header = ['Accept'=>'application/json' , 'content-type'=>'application/json'];
            $client = new \GuzzleHttp\Client();
            $request  = new \GuzzleHttp\Psr7\Request( 'get' , $url,$header  );
            $response = $client->send($request,['timeout' => 30]);
      }catch (Exception $e){
            return false;
      }
      if($response->getStatusCode() != 200){

        return false;
      }
      $data =   $response->getBody()->getContents();
      $data =   json_decode($data , true);
      $rate =   isset($data['query']['results']['rate'])?$data['query']['results']['rate']:$data['query']['results']['rate']['Rate'] = false;
      return $rate['Rate'];
    }


}