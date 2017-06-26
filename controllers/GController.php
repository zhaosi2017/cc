<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class GController extends Controller
{
    public $layout = '@app/views/layouts/right';

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
                        'actions' => ['index','captcha','code','complete','find-password-one','find-password-two','find-password-three','find-password-complete',
                            'phone-index','mobile-code','forget-password','phone-find-password','phone-password-complete'],
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
     * @param array $response
     */
    public function ajaxResponse($response = ['code'=>0, 'msg'=>'操作成功', 'data'=>[]])
    {
        header('Content-Type: application/json');
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

}
