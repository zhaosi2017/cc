<?php

namespace app\modules\home\controllers;
use app\controllers\GController;
use app\modules\home\models\RegisterForm;
use Yii;
use app\modules\home\servers\MailClient;

class RegisterController extends GController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testMe' : null,
                'height' => 35,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/global';
        $model = new RegisterForm();
        if($model->load(Yii::$app->request->post())){
            if($model->validate(['username','password','rePassword'])){

                //发送验证码到邮箱 todo 使用swoole 异步发提高性能

                $captcha = $this->createAction('captcha');
                $verifyCode = $captcha->getVerifyCode(true);
                $email = $model->username;
                $data = ['email' => $email, 'verifyCode' => $verifyCode];
                $client = new MailClient();
                $client->connect();
                $res = $client->send($data);
                $client->close();
                return $this->render('email-code',['model' => $model,'email'=>$email]);
            }else{
                return $this->render('index',['model'=>$model]);
            }
        }
        return $this->render('index',['model'=>$model]);
    }

    public function actionCode()
    {
        $model = new RegisterForm();
        if($model->load(Yii::$app->request->post())){
            $this->layout = '@app/views/layouts/global';
            $captcha = $this->createAction('captcha');
            if($captcha->validate($model->code, false)){
                $model->register();
                return $this->render('complete',['model'=>$model]);
            }else{
                $model->addError('code','验证码输入不正确，请重新输入！3次输入错误，账号将被锁定1年！');
                return $this->render('code',['model'=>$model]);
            }
        }

        return $this->redirect('index');
    }

    public function actionComplete()
    {
        if(Yii::$app->request->isPost){
            return $this->redirect(['/home/login/index']);
        }
        return false;
    }
}
