<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\LoginForm;
use yii\helpers\Url;
use app\controllers\PController;
use Yii;

/**
 * Default controller for the `admin` module
 */
class LoginController extends PController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
           
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/global';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) ) {
            

            if($model->checkLock()){
                return $this->render('index',['model'=>$model]);
            }
           
            if($model->login())
            {   // 登陆成功.
                return $this->redirect(['/admin/default/index']);
            }

            $model->afterCheckLock();

        } 
        return $this->render('index',['model' => $model]);
        
    }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->redirect(Url::to(['/admin/login/index']));
    }

}
