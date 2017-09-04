<?php

namespace app\modules\home\controllers;

use app\modules\home\models\User;
use Yii;
use app\controllers\GController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
/**
 * Class PotatoController.
 *
 * 对接第三方服务potato相关业务.
 */
class HelpController extends GController
{

    public function actionGuide()
    {
//        $this->layout = '@app/views/layouts/global';
        return $this->render('guide');
    }

    public function actionSoftware()
    {
        return $this->render('software');
    }

    public function actionOnlineService()
    {
        return $this->render('online-service');
    }

    public function actionQustionAnswer()
    {
        return $this->render('question-answer');
    }

}