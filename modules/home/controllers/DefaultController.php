<?php

namespace app\modules\home\controllers;

use Yii;
use app\controllers\GController;

/**
 * Default controller for the `home` module
 */
class DefaultController extends GController
{
    public $defaultAction = 'home';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionHome()
    {
        $this->redirect('/home/default/welcome');
    }

    public function actionWelcome()
    {
        $this->layout = '@app/views/layouts/shouye';
        return $this->render('welcome');
    }

    public function actionDeny()
    {
        $this->layout = '@app/views/layouts/global';
        return $this->render('deny');
    }

}
