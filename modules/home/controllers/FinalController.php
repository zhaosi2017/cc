<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/17
 * Time: 上午10:15
 */

namespace app\modules\home\controllers;


use app\modules\home\models\CallRecord;
use app\modules\home\models\Telegram;
use app\modules\home\servers\FinalService\FinalService;
use app\modules\home\servers\TTSservice\Infobip;
use app\modules\home\servers\TTSservice\Nexmo;
use app\modules\home\servers\TTSservice\TTSservice;
use app\modules\home\servers\TTSservice\Sinch;
use Yii;
use app\controllers\GController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class FinalController extends GController
{

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
                        'actions' => ['index', 'aiyi-event'],
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
     *支付回调
     */
    public function actionAiyiEvent(){

        $data = Yii::$app->request->get();
        $service = new  FinalService();
         return   $service->Event($data);


    }



}