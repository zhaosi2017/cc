<?php

namespace app\modules\home\controllers;

use app\modules\home\models\RegisterForm;
use app\modules\home\models\User;
use Yii;
use app\controllers\GController;
use app\modules\home\models\LoginForm;
use yii\helpers\Url;

class LoginController extends GController
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
        // 已经登陆的就跳到首页.
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['/home/default/welcome']);
        }
        

        $this->layout = '@app/views/layouts/global';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $lock = $model->checkLock();
            if(isset($lock['flag']) && $lock['flag'] > 1){
                $lock['flag'] == 2 &&  $message ='已被冻结30分钟';
                $lock['flag'] == 3 &&  $message ='已被冻结24小时';
                $model->addError('username', '用户 '.$model->username. $message);
                return $this->render('index',['model'=>$model]);
            }

            if($model->preLogin()){
                $model->login();
                $model->recordIp();
                return $this->redirect(['/home/default/welcome']);
            }

            $flag = Yii::$app->redis->hget($model->username.'-'.'homenum','flag');
            if($flag == 1){
                $model->addError('username', '用户 '.$model->username. '再错一次账号将被冻结三十分钟');
            }
            if($flag == 2){
                $model->addError('username', '用户 '.$model->username. '已被冻结30分钟');
            }
            
        }

        return $this->render('index',['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->redirect(Url::to(['/home/login/index']));
    }

    public function actionFindPasswordOne()
    {
        $this->layout = '@app/views/layouts/global';
        $model = new LoginForm();
        return $this->render('find-password-one',['model' => $model]);
    }

    public function actionFindPasswordTwo()
    {
        $this->layout = '@app/views/layouts/global';
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post())){
            if(!$model->validate(['username'])){
                return $this->render('find-password-one',['model' => $model]);
            }else{
//                $register_model = new RegisterForm();

                $captcha = $this->createAction('captcha');
                $verifyCode = $captcha->getVerifyCode(true);
                $message = Yii::$app->mailer->compose();
                $email = $model->username;
                $message->setTo($email)->setSubject('验证码')->setTextBody('验证码: ' . $verifyCode);

                if($message->send()){
                    return $this->render('find-password-two',['model' =>  $model]);
                }
                return $this->render('find-password-one',['model' =>  $model]);
            }
        }
        return false;
    }

    public function actionFindPasswordThree()
    {
        $this->layout = '@app/views/layouts/global';
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post())){
            if(!$model->validate(['code'])){
                return $this->render('find-password-two',['model' => $model]);
            }else{
                $register_model = new RegisterForm();
                $register_model->username = $model->username;
                return $this->render('find-password-three',['model' => $register_model]);
            }

        }
        return false;
    }

    public function actionFindPasswordComplete()
    {
        $this->layout = '@app/views/layouts/global';
        $model = new LoginForm();
        $register_model = new RegisterForm();
        if($register_model->load(Yii::$app->request->post())){
            $model->username = $register_model->username;
            $user_model = User::findOne($model->getIdentity()->id);
            $user_model->password = $register_model->password;
            if($user_model->update()){
                Yii::$app->getSession()->setFlash('success', '操作成功');
                return $this->render('find-password-complete');
            }
        }
//        return $this->render('find-password-complete');
        return false;
    }

}
