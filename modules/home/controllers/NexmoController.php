<?php

namespace app\modules\home\controllers;

/**
 * Created by PhpStorm.
 * User: nengliu
 * Date: 2017/7/13
 * Time: 上午10:41
 */
use app\modules\home\models\Nexmo;
use yii;
use app\controllers\GController;
use yii\filters\AccessControl;

class NexmoController extends GController
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
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index',],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 回复的消息.
     */
    public function actionConference()
    {
        $nexmo = new Nexmo();
        $data = $nexmo->answer();

        echo $data;
    }

    /**
     * 处理消息.
     */
    public function actionEvent()
    {
        $nexmo = new Nexmo();
        $nexmo->event();
    }

}