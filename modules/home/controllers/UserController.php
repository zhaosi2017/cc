<?php

namespace app\modules\home\controllers;

use app\modules\home\models\ContactForm;
use app\modules\home\models\PasswordForm;
use Yii;
use app\modules\home\models\User;
use app\controllers\GController;
use yii\web\NotFoundHttpException;

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
        $this->layout = '@app/views/layouts/container';
        $model = $this->findModel(Yii::$app->user->id);
        return $this->render('index',['model'=>$model]);
    }

    public function actionSetNickname()
    {
        $model = $this->findModel(Yii::$app->user->id);
        if($model->load(Yii::$app->request->post())){
            if(empty($model->nickname)){
                $model->addError('nickname','用户昵称不能为空');
                return $this->render('set-nickname',['model'=>$model]);
            }
            if($model->update()){
                Yii::$app->getSession()->setFlash('success', '操作成功');
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
        
        if( $model->load(Yii::$app->request->post()) && $model->validate(['country_code','phone_number']) ){
            $code = $_POST['ContactForm']['code'];
            $type = Yii::$app->controller->action->id;
            if($model->validateSms($type, $code)){
                $model->addError('code', '验证码错误');
                return $this->render('set-phone-number',['model'=>$model]);
            }

            $user_model->country_code = $model->country_code;
            $user_model->phone_number = $model->phone_number;
            if($user_model->update()){
                Yii::$app->getSession()->setFlash('success', '操作成功');
                return $this->redirect(['index']);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败');
                return $this->redirect('set-phone-number');
            }
        }
        return $this->render('set-phone-number',['model'=>$model]);
    }

    public function actionDeleteNumber($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost){
            $get = Yii::$app->request->get();
            switch ($get['type']){
                case 'phone_number':
                    $model->phone_number = '';
                    $model->country_code = null;
                    break;
                case 'potato_number':
                    $model->potato_number = '';
                    $model->potato_country_code = null;
                    break;
                case 'telegram_number':
                    $model->telegram_number = '';
                    $model->telegram_country_code = null;
                    break;
            }
            if($model->update()){
                Yii::$app->getSession()->setFlash('success', '操作成功');
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败');
            }
            $this->redirect(['index']);
        }
        return false;
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
                $model->addError('code', '验证码错误');
                return $this->render('bind-potato',['model'=>$model]);
            }

            $user_model->potato_country_code = $model->potato_country_code;
            $user_model->potato_number = $model->potato_number;
            if($user_model->update()){
                Yii::$app->getSession()->setFlash('success', '操作成功');
                return $this->redirect(['index']);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败');
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
                $model->addError('code', '验证码错误');
                return $this->render('bind-telegram',['model'=>$model]);
            }
            $user_model->telegram_country_code = $model->telegram_country_code;
            $user_model->telegram_number = $model->telegram_number;
            if($user_model->update()){
                Yii::$app->getSession()->setFlash('success', '操作成功');
                return $this->redirect(['index']);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败');
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
            Yii::$app->getSession()->setFlash('success', '密码修改成功');
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
            throw new NotFoundHttpException('The requested page does not exist.');
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

    /**
     * Add urgent Contact person.
     *
     * @return User the loaded model.
     */
    public function actionAddUrgentContactPerson()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $request = Yii::$app->request->get();
        $firstPersonNoExists = empty($model->urgent_contact_person_one) ? true : false;
        $secondPersonNoExists = empty($model->urgent_contact_person_two) ? true : false;
        $modifyOne = isset($request['modify']) && ($request['modify'] == '1') ? true : false;
        $modifyTwo = isset($request['modify']) && ($request['modify'] == '2') ? true : false;
        if ($model->load(Yii::$app->request->post())) {
            
            if (($modifyOne || $firstPersonNoExists)) {
                $model->scenario  ='urgent_contact_one';
                if( !$model->validate(['urgent_contact_person_one', 'urgent_contact_one_country_code', 'urgent_contact_number_one'])){

                    return $this->render('add-urgent-contact-person-one', ['model' => $model,'isModify'=>$modifyOne]);
                }
                 $updateRes = $model->update();
                 Yii::$app->getSession()->setFlash('success', '操作成功');
                 return $this->redirect(['index']);
            }

            if (($modifyTwo || $secondPersonNoExists)) {
                $model->scenario  ='urgent_contact_two';
                if(!$model->validate(['urgent_contact_person_two', 'urgent_contact_two_country_code', 'urgent_contact_number_two'])){
                    return $this->render('add-urgent-contact-person-two', ['model' => $model,'isModify'=>$modifyTwo]);
                }
                 $updateRes = $model->update();
                 Yii::$app->getSession()->setFlash('success', '操作成功');
                 return $this->redirect(['index']);
                
            }
            return $this->redirect(['index']);
        } else {
            $isModify = false;
            // 修改紧急联系人.
            if (isset($request['modify'])) {
                $isModify = true;
                if ($modifyOne) {
                    return $this->render('add-urgent-contact-person-one', ['model' => $model, 'isModify' => $isModify]);
                } elseif ($modifyTwo) {
                    return $this->render('add-urgent-contact-person-two', ['model' => $model, 'isModify' => $isModify]);
                }
            } else {
                // 判断用户添加几位紧急联系人, 当两位联系人没有满, 才能继续添加联系人.
                if ($firstPersonNoExists) {
                    return $this->render('add-urgent-contact-person-one', ['model' => $model, 'isModify' => $isModify]);
                } elseif ($secondPersonNoExists) {
                    return $this->render('add-urgent-contact-person-two', ['model' => $model, 'isModify' => $isModify]);
                } else {
                    Yii::$app->getSession()->setFlash('error', '只能添加两位紧急联系人!');
                    return $this->redirect(['index']);
                }
            }
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
        $model = $this->findModel(Yii::$app->user->id);
        if ($request['type'] == '1') {
            $model->urgent_contact_person_one = '';
            $model->urgent_contact_one_country_code = '';
            $model->urgent_contact_number_one = '';
        } elseif ($request['type'] == '2') {
            $model->urgent_contact_person_two = '';
            $model->urgent_contact_two_country_code = '';
            $model->urgent_contact_number_two = '';
        }

        $model->update();
        return $this->redirect(['index']);
    }

}
