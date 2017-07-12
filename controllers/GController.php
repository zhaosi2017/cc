<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\home\models\LoginForm;

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
//                       'controllers' => ['/home/login'],
                        'actions' => ['home','welcome','login','register','change-language','captcha','code','complete','find-password-one','find-password-two','find-password-three','find-password-complete',
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


    public function beforeAction($event)
    {


        if (!Yii::$app->user->isGuest)
        {
            $identy = Yii::$app->user->identity;
            Yii::$app->language = $identy->language;
            if($url = $this->checkTutoria()){
                header("Location:". $url);
                return false;
            }
        }else{
            Yii::$app->language = isset($_SESSION['language'])? $_SESSION['language']:'zh-CN';
        }

        return parent::beforeAction($event);
    }

    private  function checkTutoria()
    {

        $url = '/'.Yii::$app->request->getPathInfo();
        $arr = ['/home/user/set-phone-number', '/home/potato/bind-potato', '/home/telegram/bind-telegram','/home/user/bind-username','/home/user/bind-email'];
        if( !in_array($url,$arr) && Yii::$app->request->isGet )
        {
            $res = LoginForm::checkFlash();
            $message = isset($res['message']) ? $res['message']:'';
            $message && Yii::$app->getSession()->setFlash('step-message', $message);
            return isset($res['url'])? $res['url']:'';
        }

    }



}
