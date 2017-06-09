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
            $lock = $model->checkLock();
           
            if(isset($lock['flag']) && $lock['flag'] > 1){
                $lock['flag'] == 2 &&  $message ='已被冻结30分钟';
                $lock['flag'] == 3 &&  $message ='已被冻结24小时';
                $model->addError('username', '用户 '.$model->username. $message);
                return $this->render('index',['model'=>$model]);
            }
            if($model->login())
            {
                return $this->redirect(['/admin/default/index']);
            }
            $flag = Yii::$app->redis->hget($model->username.'-'.'adminnum','flag');
            if($flag == 1){
                $model->addError('username', '用户 '.$model->username. '再错一次账号将被冻结三十分钟');
            }
            if($flag == 2){
                $model->addError('username', '用户 '.$model->username. '已被冻结30分钟');
            }

        
            // 登陆成功.
            
        } 
        return $this->render('index',['model' => $model]);
        
    }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->redirect(Url::to(['/admin/login/index']));
    }

}
