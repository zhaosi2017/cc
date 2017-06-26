<?php

namespace app\modules\home\controllers;
use app\controllers\GController;
use app\modules\home\models\PhoneRegisterForm;
use app\modules\home\models\RegisterForm;
use Yii;
use app\modules\home\servers\MailClient;
use app\modules\home\models\ContactForm;
use app\modules\home\models\UserPhone;

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


    public function actionPhoneIndex()
    {
       
        $this->layout = '@app/views/layouts/global';
     
        $model = new PhoneRegisterForm();
        $model->setScenario('register');
        if($model->load(Yii::$app->request->post())){
            if($model->validate(['phone','password','rePassword'])){

                //发送验证码到邮箱 todo 使用swoole 异步发提高性能
                $code =$_POST['PhoneRegisterForm']['code'];
                $type = Yii::$app->controller->action->id;
                if(ContactForm::validateSms($type, $code)){
                    $model->addError('code', '验证码错误');
                    return $this->render('phone-index',['model'=>$model]);
                }
                if($model->register()){
                    return $this->redirect('/home/login/index')->send();
                }
                Yii::$app->getSession()->setFlash('error', '操作失败');

            }else{
                return $this->render('phone-index',['model'=>$model]);
            }
        }
        return $this->render('phone-index',['model'=>$model]);
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


    public function actionPhoneCode()
    {
        $model = new PhoneRegisterForm();
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

    public function actionMobileCode()
    {

        if(Yii::$app->request->isAjax){
            $number = Yii::$app->request->post('number');
            $type = Yii::$app->request->post('type');
            if($number && $type ){
                if( $response = ContactForm::smsRateLimit($type)){
                    exit(json_encode($response));
                }
                $session = Yii::$app->session;
                $verifyCode = $session[$type] = ContactForm::makeCode();
                 $url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
                     [
                         'api_key' =>  Yii::$app->params['nexmo_api_key'],
                         'api_secret' => Yii::$app->params['nexmo_api_secret'],
                         'to' => $number,
                         'from' => Yii::$app->params['nexmo_account_number'],
                         'text' => $verifyCode
                     ]
                 );

                 $ch = curl_init($url);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 $response = curl_exec($ch);
                 $response = json_decode($response, true);
                 $response['code'] = $verifyCode;
                 $response = json_encode($response);
                 echo $response;
            }
        }
        return false;
    
    }

    public function actionComplete()
    {
        if(Yii::$app->request->isPost){
            return $this->redirect(['/home/login/index']);
        }
        return false;
    }
}
