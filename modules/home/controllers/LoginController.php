<?php

namespace app\modules\home\controllers;

use app\modules\home\models\PhoneRegisterForm;
use app\modules\home\models\ContactForm;
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

    public function actionLogin()
    {
        // 已经登陆的就跳到首页.
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['/home/default/welcome']);
        }

        $this->layout = '@app/views/layouts/global';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            if($model->checkLock()){
                return $this->render('index',['model'=>$model]);
            }
            if($model->login()) {
                $model->recordIp();
                $user = Yii::$app->user->identity;
                $res = LoginForm::checkLearn();
                if($user->step == 0 && !empty($res)){
                    $url =  array_shift($res);
                    $type = isset($url['type']) ? $url['type'] : '';
                    $type && Yii::$app->getSession()->hasFlash($type) && Yii::$app->getSession()->removeFlash($type);
                    isset($url['message']) && Yii::$app->getSession()->setFlash('step-message',$url['message']);
                    return $this->redirect($url['url'])->send();
                }
                return $this->goBack()->send();
            }
            
            $model->afterCheckLock();
            return $this->render('index',['model' => $model]);
        }

        return $this->render('index',['model' => $model]);
    }

    public function actionLogout()
    {
        LoginForm::clearFlash();
        Yii::$app->user->logout(false);
        return $this->redirect(Url::to(['/home/default/welcome']));
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
                $captcha = $this->createAction('captcha');
                $verifyCode = $captcha->getVerifyCode(true);
                $message = Yii::$app->mailer->compose();
                $email = $model->username;
                $message->setTo($email)->setSubject(Yii::t('app/index','Verification code'))->setTextBody(Yii::t('app/index','Verification code').' : ' . $verifyCode);
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
                $model->deleteLoginNum();
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                return $this->render('find-password-complete');
            }
        }
//        return $this->render('find-password-complete');
        return false;
    }


    public function actionForgetPassword()
    {
        $this->layout = '@app/views/layouts/global';
        return $this->render('forget-password');
    }

    public function actionPhoneFindPassword()
    {
        $this->layout = '@app/views/layouts/global';
        $model = new PhoneRegisterForm();
        $model->setScenario('find-password');
        if($model->load(Yii::$app->request->post()) )
        {
            if($model->validate('country_code','phone','code')){
                $code =$_POST['PhoneRegisterForm']['code'];
                $type = Yii::$app->controller->action->id;
                if(ContactForm::validateSms($type, $code)){
                    $model->addError('code', Yii::t('app/index','Verification code error'));
                    return $this->render('phone-find-password',['model'=>$model]);
                }
                $model->setScenario('update-password');
                return $this->render('phone-find-password-one',['model'=>$model]);
            }

        }
        return $this->render('phone-find-password',['model'=>$model]);
    }

    public function actionPhonePasswordComplete()
    {
        $this->layout = '@app/views/layouts/global';
        $register_model = new PhoneRegisterForm();

        if($register_model->load(Yii::$app->request->post())){

            if($register_model->updatePassword()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                return $this->redirect('/home/login/login');
            }else{
                Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
                return $this->redirect('/home/login/phone-find-password');
            }
        }

        return false;
    }

    public function actionChangeLanguage()
    {
        if (Yii::$app->request->isPost) {
            $language = $_POST['language'];
            $languages = Yii::$app->params['languages'];
            if(isset($languages[$language])){
                $session = Yii::$app->session;
                $session['language'] = $language;
                    return json_encode(['status'=>0]);
            }
        }
        return json_encode(['status'=>1]);
    }

}
