<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/15
 * Time: 上午10:13
 */

namespace app\modules\home\controllers;


use app\modules\home\models\CallRecord;
use app\modules\home\servers\TTSservice\Nexmo;
use app\modules\home\servers\TTSservice\TTSservice;
use app\modules\home\servers\TTSservice\Sinch;
use Yii;
use app\controllers\GController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class TtsController extends GController{


    /**
     *sinch 的回调
     */
    public function actionSinchEvent(){

        $arr = array (
            'event' => 'dice',
            'callid' => '94897162-b66d-4857-a014-147e48fdcf18',
            'timestamp' => '2017-07-15T03:07:17Z',
            'reason' => 'MANAGERHANGUP',
            'result' => 'NOANSWERED',
            'version' => 1,
            'custom' => 'customData',
            'user' => '',
            'debit' =>
                array (
                    'currencyId' => 'USD',
                    'amount' => 0.076200000000000004,
                ),
            'userRate' =>
                array (
                    'currencyId' => 'USD',
                    'amount' => 0.076200000000000004,
                ),
            'to' =>
                array (
                    'type' => 'number',
                    'endpoint' => '85586564836',
                ),
            'applicationKey' => '893b8449-294a-4ee7-8f5f-0248d76588b7',
            'duration' => 2,
            'from' => '',
        );


echo "<pre>";
        //$callback_data = Yii::$app->request->post();
        $service = TTSservice::init(Sinch::class);
        $rest = $service->event($arr);
        echo $rest;
    }


    /**
     *nexmo 语音播报
     */
    public function actionNexmoAnwser(){

        $cachKey = Yii::$app->request->get('key');
        header("content-type:application/json;charset=utf-8");
        $data = Yii::$app->redis->get($cachKey);
        if(empty($data)){
            $data   = '[]';
        }
        echo $data;
        die();
    }

    /**
     * nexmo 应答回调
     */
    public function actionNexmoEvent(){
        $postData = @file_get_contents('php://input');
        $postData = json_decode($postData, true);
        $service = TTSservice::init(Nexmo::class);
        $result =  $service->event($postData);
        echo $result;
    }

}