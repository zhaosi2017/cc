<?php

namespace app\modules\home\controllers;

use app\modules\home\models\ContactForm;
use app\modules\home\models\EmailForm;
use app\modules\home\models\LoginForm;
use app\modules\home\models\PasswordForm;
use app\modules\home\models\PhoneRegisterForm;
use app\modules\home\models\UserGentContact;
use app\modules\home\models\UserPhone;
use Yii;
use app\modules\home\models\User;
use app\controllers\GController;
use yii\web\NotFoundHttpException;
use app\modules\home\servers\MailClient;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends GController
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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $user_phone_numbers = UserPhone::findAll(array('user_id'=>Yii::$app->user->id));  //取用户的全部绑定电话
        $user_gent_contacts  =  UserGentContact::findAll(array('user_id'=>Yii::$app->user->id));   //取全部的紧急联系人
        return $this->render('index',['model'=>$model , 'user_phone_numbers'=>$user_phone_numbers , 'user_gent_contents'=>$user_gent_contacts]);
    }

    public function actionSetNickname()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario='bind-nickname';
        if($model->load(Yii::$app->request->post())){
            
            if($model->update()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                return $this->redirect(['index']);
            }
            
        }
        return $this->render('set-nickname',['model'=>$model]);
    }

    public function actionSetPhoneNumber()
    {
        $id = Yii::$app->user->id;
        $model = (new ContactForm())->findModel($id);
        $user_model = $this->findModel($id);
        $model->scenario='phone';
        $isModify = false;
        $phone_number = $this->getUserPhoneNumber(Yii::$app->request->get('phone_number'));
        if(!empty($phone_number->user_phone_number)){    //已经有电话号码的  显示电话号码
            $isModify = true;
        }
        $model->phone_number = $phone_number->user_phone_number;
        $model->country_code = $phone_number->phone_country_code;
        if( $model->load(Yii::$app->request->post()) && $model->validate(['country_code','phone_number']) ){
            $code = $model->code;
            $user_phone_number = $model->phone_number;
            $phone_country_code = $model->country_code;
            $type = Yii::$app->controller->action->id;
            if($model->validateSms($type, $code)){
                $model->addError('code', Yii::t('app/index','Verification code error'));
                return $this->render('set-phone-number',['model'=>$model,'isModify'=>$isModify]);
            }
            $phone_number->user_phone_number = $user_phone_number;
            $phone_number->phone_country_code = $phone_country_code;
            $phone_number->user_id = Yii::$app->user->id;
            if($phone_number->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                return $this->redirect(['/home/user/links']);
            }else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
                return $this->redirect('set-phone-number');
            }
        }
        return $this->render('set-phone-number',['model'=>$model, 'isModify' => $isModify]);
    }

    /**
     * @param null $phone_number 电话号码
     * 根据已有的电话号码选择号码的详细信息
     */
    private function getUserPhoneNumber($phone_number = null){
        $res = array();
        if(empty($phone_number)){
            $phone_number = new UserPhone();
            $phone_number->user_phone_number ='';
            $phone_number->phone_country_code ='';
         }else{
            $phone_number =  UserPhone::findOne(array('user_phone_number'=>$phone_number , 'user_id'=>Yii::$app->user->id));
        }
        return $phone_number;
    }



    public function actionDeleteNumber()
    {
        $model = $this->findModel(Yii::$app->user->id);
        if(Yii::$app->request->isPost){
            $get = Yii::$app->request->get();
            switch ($get['type']){
                case 'phone_number':
                    $this->deletePhoneNumber($get['phone_number'] , $get['country_code']);
                    $url = '/home/user/links';
                    break;
                case 'potato_number':
                    $model->potato_number = '';
                    $model->potato_country_code = null;
                    $url = '/home/user/app-bind';
                    break;
                case 'telegram_number':
                    $model->telegram_number = '';
                    $model->telegram_country_code = null;
                    $url = '/home/user/app-bind';
                    break;
            }
            if($model->update()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
            }else{
                Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
            }
            $this->redirect([$url]);
        }
        return false;
    }

    /**
     * @param $phone_number
     * 删除电话号码
     */
    private function deletePhoneNumber($phone_number , $country_code)
    {
        $model =  UserPhone::findOne(array('user_phone_number'=>$phone_number , 'user_id'=>Yii::$app->user->id , 'phone_country_code'=>$country_code));
        if($model->delete()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
        }else{
            Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
        }
        $this->redirect(['index']);
    }


    public function actionBindPotato()
    {
        $id = Yii::$app->user->id;
        $model = (new ContactForm())->findModel($id);
        $user_model = $this->findModel($id);
        $model->scenario='potato';

        if($model->load(Yii::$app->request->post()) && $model->validate(['potato_country_code','potato_number'])){
            $code = $_POST['ContactForm']['code'];
            $type = Yii::$app->controller->action->id;
            if($model->validateSms($type, $code)){
                $model->addError('code', Yii::t('app/index','Verification code error'));
                return $this->render('bind-potato',['model'=>$model]);
            }

            $user_model->potato_country_code = $model->potato_country_code;
            $user_model->potato_number = $model->potato_number;
            if($user_model->update()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                return $this->redirect(['index']);
            }else{
                Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
                return $this->redirect('bind-potato');
            }
        }
        return $this->render('bind-potato',['model'=>$model]);
    }

    public function actionBindTelegram()
    {
        $id = Yii::$app->user->id;
        $model = (new ContactForm())->findModel($id);
        $user_model = $this->findModel($id);
        $model->scenario='telegram';

        if($model->load(Yii::$app->request->post()) && $model->validate(['telegram_country_code','telegram_number'])){
            
            $code = $_POST['ContactForm']['code'];
            $type = Yii::$app->controller->action->id;
            if($model->validateSms($type,$code)){
                $model->addError('code', Yii::t('app/index','Verification code error'));
                return $this->render('bind-telegram',['model'=>$model]);
            }
            $user_model->telegram_country_code = $model->telegram_country_code;
            $user_model->telegram_number = $model->telegram_number;
            if($user_model->update()){
                Yii::$app->getSession()->setFlash('success',  Yii::t('app/index','Successful operation'));
                return $this->redirect(['index']);
            }else{
                Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
                return $this->redirect('bind-telegram',['model'=>$model]);
            }
        }
        return $this->render('bind-telegram',['model'=>$model]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionPassword()
    {
        $model = new PasswordForm();
        if($model->load(Yii::$app->request->post()) && $model->updateSave()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Password reset complete'));
            return $this->redirect('/home/user/index');
        }
        return $this->render('password',['model' => $model]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app/index','The requested page does not exist'));
        }
    }

    public function actionSendShortMessage()
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
                        'text' => $verifyCode.' : ( From callu code )'
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

    /**
     * Add urgent Contact person.
     *
     * @return User the loaded model.
     */
    public function actionAddUrgentContactPerson()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $request = Yii::$app->request->get();
        if (!empty(Yii::$app->request->post('UserGentContact'))) {

            $contact_id = isset($request['id'])?$request['id'] :'';

            if(empty($contact_id)){
                $contact = new UserGentContact();
                $contact->attributes = Yii::$app->request->post('UserGentContact');
                if(empty(Yii::$app->request->post('UserGentContact')['contact_nickname'])){
                    Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
                    return $this->redirect(['/home/user/links']);

                }
                if(empty(Yii::$app->request->post('UserGentContact')['contact_country_code'])){
                    Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
                    return $this->redirect(['/home/user/links']);

                }
                if(empty(Yii::$app->request->post('UserGentContact')['contact_phone_number'])){
                    Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
                    return $this->redirect(['/home/user/links']);

                }

            }else{
                $contact = UserGentContact::findOne($contact_id);
            }
            $contact->contact_nickname      = Yii::$app->request->post('UserGentContact')['contact_nickname'];
            $contact->contact_country_code  = Yii::$app->request->post('UserGentContact')['contact_country_code'];
            $contact->contact_phone_number  = Yii::$app->request->post('UserGentContact')['contact_phone_number'];
            if($contact->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                return $this->redirect(['/home/user/links']);
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
            return $this->redirect(['/home/user/links']);

        } else {
            $isModify = false;
            // 修改紧急联系人.
            $contact_id = isset($request['id'])?$request['id'] :'';      //紧急联系人的id
            $contact = UserGentContact::findOne($contact_id);
            if(empty($contact)){
                $contact = new UserGentContact();
                $contact->contact_nickname="";
                $contact->contact_phone_number ='';
                $contact->contact_country_code ='';
                return $this->render('add-urgent-contact-person', ['model' =>$contact , 'isModify' => $isModify]);
            }
            $isModify = true;
            return $this->render('add-urgent-contact-person', ['model' =>$contact , 'isModify' => $isModify]);
        }
    }

    /**
     * 删除紧急联系人.
     *
     * @return User the loaded model.
     */
    public function actionDeleteUrgentContactPerson()
    {
        $request = Yii::$app->request->get();
        $contact_id = $request['id'];
        $contact = UserGentContact::findOne($contact_id);
        if($contact->delete()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
        }else{
            Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
        }

        return $this->redirect(['/home/user/links']);
    }


    public function actionHarassment()
    {   
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        $model->scenario = 'harassment';
        if($model->load(Yii::$app->request->post()) && $model->validate()){

            if($model->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
            }else{
                Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
            }
            return $this->redirect(['/home/user/harassment']);
        }
        return $this->render('harassment', [
            'model' => $model,
        ]);
    }


    public function actionBindUsername()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        $model->scenario = 'bind-username';
        if($model->load(Yii::$app->request->post()) && $model->validate('username')){
            if($model->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
            }else{
                Yii::$app->getSession()->setFlash('error',  Yii::t('app/index','Operation failed'));
            }
            return $this->redirect(['index']);
        }
        return $this->render('bind-username', [
            'model' => $model,
        ]);
    }

    public function actionBindEmail()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        $model->scenario = 'bind-email';//邮箱
        if($model->load(Yii::$app->request->post()) && $model->validate('account')){
            $emailModel = new EmailForm();
            $key = $model->account.'bindemail';
            $session = Yii::$app->session;
            $session[$key]   =  $verifyCode = ContactForm::makeCode();
            $email = $emailModel->username =$model->account;
            $data = ['email' => $email, 'verifyCode' => $verifyCode];
            $client = new MailClient();
            $client->connect();
            $res = $client->send($data);
            $client->close();
            return $this->render('bind-email-code',['model'=>$emailModel]);
        }
        return $this->render('bind-email', [
            'model' => $model,
        ]);
    }

    public  function actionBindEmailCode()
    {
        $model = new EmailForm();
        if($model->load(Yii::$app->request->post())){
            if($model->validate(['username','code'])){
                $id = Yii::$app->user->id;
                $userModel = $this->findModel($id);
                $userModel->account = $model->username;
                $userModel->save();
                Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Mailbox binding is successful'));
                return $this->redirect('/home/user/index')->send();

            }else{
                return $this->render('bind-email-code',['model'=>$model]);
            }

        }
        return false;
    }


    public function actionAppBind()
    {
        $id = Yii::$app->user->id;
        $model = User::findOne($id);
        return $this->render('app-bind', ['model' => $model]);
    }

    public function actionLinks()
    {
        $id = Yii::$app->user->id;
        $userPhone = UserPhone::findAll(['user_id'=>$id]);
        $urgentContact = UserGentContact::findAll(['user_id'=>$id]);
        return $this->render('links', ['userPhone' => $userPhone,'urgentContact'=>$urgentContact]);
    }


    public function actionUpdatePhoneNumber()
    {
        $id = Yii::$app->user->id;
        $userModel = $this->findModel($id);
        $model = new PhoneRegisterForm();
        $model->phone = $userModel->phone_number;
        $model->country_code = $userModel->country_code;
        $model->setScenario('update-phone');
        if(Yii::$app->request->isPost){

            if($model->load(Yii::$app->request->post()) )
            {

                if( $model->load(Yii::$app->request->post()) && $model->validate('country_code','phone','code')){

                    $code =$_POST['PhoneRegisterForm']['code'];
                    $type = Yii::$app->controller->action->id;
                    if(ContactForm::validateSms($type, $code)){
                        $model->addError('code', Yii::t('app/index','Verification code error'));
                        return $this->render('update-phone-number',['model'=>$model]);
                    }
                    $userModel->phone_number = $model->phone;
                    $userModel->country_code = $model->country_code;
                    $userModel->save();
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
                    return $this->redirect('/home/user/index')->send();
                }
            }
        }

        return $this->render('update-phone-number',['model'=>$model]);
    }


    public function actionChangeLanguage(){
        if (Yii::$app->request->isPost) {
            $language = $_POST['language'];
            $languages = Yii::$app->params['languages'];
            if(isset($languages[$language])){
                $id = Yii::$app->user->id;
                $user = User::findOne($id);
                $user->language = $language;
                if ($user->save()){
                    $session = Yii::$app->session ;
                    $session['language'] = $language;
                    return json_encode(['status'=>0]);
                }
            }
        }
        return json_encode(['status'=>1]);
    }

}
