<?php

namespace app\modules\home;
use Yii;
/**
 * home module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\home\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
        Yii::$app->user->identityClass = 'app\modules\home\models\User';
        Yii::$app->user->enableAutoLogin = true;
    }
}
