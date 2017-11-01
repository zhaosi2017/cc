<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/15
 * Time: 上午10:13
 */

namespace app\modules\home\controllers;


use app\modules\home\models\CallRecord;
use app\modules\home\models\Telegram;
use app\modules\home\servers\TTSservice\Nexmo;
use app\modules\home\servers\TTSservice\TTSservice;
use app\modules\home\servers\TTSservice\Sinch;
use Yii;
use app\controllers\GController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class TtsController extends GController{

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                            'actions' => ['index' , 'sinch-event' ,'nexmo-anwser' , 'nexmo-event' ,'test-sinch'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }
    /**
     *sinch 的回调
     */
    public function actionSinchEvent(){


        $postData = @file_get_contents('php://input');
        file_put_contents('/tmp/sinch-event'.date('Y-m-d').'.log' , var_export($postData , true).PHP_EOL , 8);
        $callback_data = json_decode($postData ,true);
        $service = TTSservice::init(Sinch::class);
        $rest = $service->event($callback_data);
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