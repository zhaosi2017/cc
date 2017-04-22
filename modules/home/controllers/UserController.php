<?php

namespace app\modules\home\controllers;

use app\modules\home\models\PasswordForm;
use Yii;
use app\modules\home\models\User;
use app\controllers\GController;
use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;

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
        if($model->load(Yii::$app->request->post()) && $model->update()){
            return $this->redirect(['index']);
        }
        return $this->render('set-nickname',['model'=>$model]);
    }

    public function actionSetPhoneNumber()
    {
        $model = $this->findModel(Yii::$app->user->id);
        if($model->load(Yii::$app->request->post()) && $model->update()){
            return $this->redirect(['index']);
        }
        return $this->render('set-phone-number',['model'=>$model]);
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
            if($number){
                $url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
                    [
                        'api_key' =>  Yii::$app->params['nexmo_api_key'],
                        'api_secret' => Yii::$app->params['nexmo_api_secret'],
                        'to' => $number,
                        'from' => Yii::$app->params['nexmo_account_number'],
                        'text' => '2345'
                    ]
                );

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                echo $response;
            }
        }
        return false;
    }

}
