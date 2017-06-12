<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\modules\admin\models\Manager;

class PController extends Controller
{
    public $layout = '@app/views/layouts/right_admin';

    /**
     * @inheritdoc
     */
    public function beforeAction($event)
    {

        if( Yii::$app->requestedRoute == 'admin/login/index' || Yii::$app->requestedRoute == 'admin/login/captcha' ){
            return true;
        }
        if (Yii::$app->user->isGuest)
        {
           return  $this->redirect(['/admin/login/index'])->send();
        }
        /**
         * 登陆后不需要检查的权限数组
         */
        $arr = [
            'admin/default/deny',
            'admin/login/logout',
        ];

        $identity = (Object) Yii::$app->user->identity;
       
        if ( in_array(Yii::$app->requestedRoute, $arr)){
            return true;
        }
        if( !Yii::$app->user->can(Yii::$app->requestedRoute)  ) {
            return  $this->redirect(Url::to(['/admin/default/deny']))->send();
            return false;
        }
        return parent::beforeAction($event);
    }


    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    /*public function beforeAction($action)
    {
        $this->layout = '@app/views/layouts/right_admin';

        Yii::$app->controller->id != 'login'
        && Yii::$app->user->isGuest
        && $this->redirect(['/admin/login/index']);

        return parent::beforeAction($action);

    }*/

    /**
     * @param array $response
     */
    public function ajaxResponse($response = ['code'=>0, 'msg'=>'操作成功', 'data'=>[]])
    {
        header('Content-Type: application/json');
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

}
