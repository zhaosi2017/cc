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
                        'actions' => ['index', 'event', 'conference'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
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
        $cachKey = Yii::$app->request->get('key');
        $nexmo = new Nexmo();
        $nexmo->setAnswerKey($cachKey);
        header("content-type:application/json;charset=utf-8");

        $data = $nexmo->answer();
        echo $data;
        die();
    }

    /**
     * 处理消息.
     */
    public function actionEvent()
    {
        $postData = @file_get_contents('php://input');
        $postData = json_decode($postData, true);
        if (!empty($postData)) {
            $nexmo = new Nexmo();
            $nexmo->setEventData($postData);
            return $nexmo->event();
        }
    }

}