<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class GController extends Controller
{
    public $layout = '@app/views/layouts/global';

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
                        'actions' => ['delete'],
                        'allow' => false,
                    ],
                    [
                        'allow' => true,
//                        'controllers' => ['/home/login'],
                        'actions' => ['index','captcha','code','complete','find-password-one','find-password-two','find-password-three','find-password-complete'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->layout = '@app/views/layouts/right';

        Yii::$app->controller->id != 'register'
        && Yii::$app->controller->id != 'login'
        && Yii::$app->user->isGuest
        && $this->redirect(['/home/login/index']);

        return parent::beforeAction($action);

    }

    /**
     * @param array $response
     */
    public function ajaxResponse($response = ['code'=>0, 'msg'=>'操作成功', 'data'=>[]])
    {
        header('Content-Type: application/json');
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

}
