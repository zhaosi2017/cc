<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 下午3:12
 */
namespace app\modules\home\servers\appService;

use app\modules\home\models\User;
use Codeception\Lib\Connector\Guzzle;

class AppPotato extends AbstractApp{
    /**
     * @var int
     * 内链消息id
     */
    public $inlineMessageId;



    /**
     * @param $data
     * 解析回调的数据
     * @return string
     */
    public  function Event($data){

        $data = json_decode($data, true);
        $message = isset($data['result']) ? $data['result'] : array();
        if(empty($message)){
            return '';
        }
        $this->from_user->app_uid        = isset($message['sender_id'])?$message['sender_id']:$message['user_id'];
        $this->from_user->app_first_name = isset($message['sender_first_name']) ? $message['sender_first_name'] : '';
        $this->from_user->app_last_name  = isset($message['sender_last_name']) ? $message['sender_last_name'] : '';
        $this->from_user->model_user     = User::findOne(['potato_user_id'=> $this->from_user->app_uid]);


        if($message['request_type'] == 1){              //普通消息
            $this->callbcakData->text        = isset($message['text'])?$message['text']:'';
            if( $this->callbcakData->text == '/start'){
                $this->callbcakData->CallBack_type = AppCallBack::CALLBACK_TYPE_TEXT;   //第一次使用
            }elseif($this->callbcakData->text == '/maps'){
                $this->callbcakData->CallBack_type = AppCallBack::CALLBACK_TYPE_MAP;    //地图查询  判定条件需重新审订
            }elseif (isset($message['reply_to_message']) && !empty($message['reply_to_message'])){
                $this->callbcakData->CallBack_type = AppCallBack::CALLBACK_TYPE_REPLAY; //回复消息
            }
        }elseif($message['request_type'] == 2){          //按钮回调

            $this->callbcakData->callback_data->setStringData($message['data']);
            $this->callbcakData->CallBack_type = AppCallBack::CALLBACK_TYPE_BUTTON;
        }elseif($message['request_type'] == 3) {

        }elseif($message['request_type'] == 4){             //分享了名片
            $this->to_user->app_uid                         = $message['user_id'] ;
            $this->to_user->app_phone_number                = str_replace(array('+', ' '), '', $message['phone_number']);
            $this->to_user->app_first_name                  = isset($message['first_name']) ? $message['first_name'] : '';
            $this->to_user->app_last_name                   = isset($message['last_name']) ? $message['last_name'] : '';
            $this->to_user->model_user                      = User::findOne(['potato_user_id'=> $this->to_user->app_uid]);
            if($this->to_user->app_uid == $this->from_user->app_uid ){
                $this->callbcakData->CallBack_type = AppCallBack::CALLBACK_TYPE_MYSELF;//分享了自己的名片
            }else{
                $this->callbcakData->CallBack_type = AppCallBack::CALLBACK_TYPE_CONTACT; //分享了别人的名片
            }
        }elseif($message['request_type'] == 5 || $message['request_type'] == 6){

             //这里是接受的地图信息处理业务

        }

        $this->inlineMessageId           = isset($message['inline_message_id']) ? $message['inline_message_id'] : '';

        if(empty( $this->inlineMessageId)){
            $this->Anwser();
        }

        return $this->callbcakData;
    }

    /**
     * @return mixed
     * 像app发送消息
     */
    public  function Send(){
        $data = ['chat_type'=>1,
                 'chat_id'=>$this->from_user->app_uid,
                 'text'=> $this->send_data->text,
                ];

        if(!empty($this->send_data->menu)){
            $menu = [];
            foreach ($this->send_data->menu as $key=>$value){

                $menu[$key] = [];
                foreach($value as $k=>$v){
                    $menu[$key][$k]['type'] = 0;         //按钮
                    $menu[$key][$k]['data'] = $v['data'];//回调预制数据
                    $menu[$key][$k]['text'] = $v['text'];//按钮的文本
                }
            }
            $data['inline_markup'] = $menu;
        }

       return $this->_Send_data($data);

    }

    /**
     * @return mixed
     * 应答app的回调
     */
    public  function Anwser(){

        $uri = 'https://bot.potato.im:5423/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendCallbackAnswer';
        $data = [
            'user_id' => $this->from_user->app_uid,
            'inline_message_id' => $this->inlineMessageId,
        ];
        $header = ['cache-control'=>'no-cache' , 'content-type'=>'application/json'];
        $body = json_encode($data, true);
        $client = new \GuzzleHttp\Client();
        $request  = new \GuzzleHttp\Psr7\Request('POST' , $uri , $header , $body);
        $response = $client->send($request,['timeout' => 30]);
        if($response->getStatusCode() != 200){
            return false;
        }
        return true;
    }

    /**
     * 发送数据给potato
     * @param $data
     * @return bool
     */
    private function _Send_data($data){
        if(isset($data['inline_markup'])){
            $uri = 'https://bot.potato.im:5423/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendInlineMarkupMessage';
        }else{
            $uri = 'https://bot.potato.im:4235/8008682:WwtBFFeUsMMBNfVU83sPUt4y/sendTextMessage';
        }
        $header = ['cache-control'=>'no-cache' , 'content-type'=>'application/json'];
        $body = json_encode($data, true);

        $client = new \GuzzleHttp\Client();
        $request  = new \GuzzleHttp\Psr7\Request('POST' , $uri , $header , $body);
        $response = $client->send($request,['timeout' => 30]);
        if($response->getStatusCode() != 200){
            return false;
        }
        return true;
    }


    /**
     * 设置验证码.
     */
    public function setCode()
    {
        $dealData = [
            Yii::$app->params['potato_pre'],
            $this->to_user->app_uid,
            $this->to_user->app_phone_number,
            $this->to_user->getName()
        ];

        $dealData = implode('-', $dealData);
        $code = rand(1000,9999);
        $potatoData = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['potato']));
        // 验证码过期时间半小时.
        Yii::$app->redis->setex($code, 30*60, $potatoData);
        return $code;
    }


}