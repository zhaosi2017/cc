<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\LoginForm;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * Default controller for the `admin` module
 */
class LoginController extends Controller
{
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // 登陆成功.
            $this->redirect(['/admin/default/index']);
        } else {
            return $this->render('index',['model' => $model]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(Url::to(['/admin/login/index']));
    }

}
