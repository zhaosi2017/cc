<?php

namespace app\modules\home\controllers;

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
        $this->layout = '@app/views/layouts/global';
        return $this->render('index');
    }

    public function actionWelcome()
    {
        return $this->render('index');
    }
}
