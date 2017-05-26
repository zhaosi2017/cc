<?php

namespace app\modules\home\controllers;

use Yii;
use app\controllers\GController;

/**
 * Default controller for the `home` module
 */
class DefaultController extends GController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect('/home/login/index');
        } else {
            $this->redirect('/home/default/welcome');
        }
    }

    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    public function actionDeny()
    {
        $this->layout = '@app/views/layouts/global';
        return $this->render('deny');
    }

}
