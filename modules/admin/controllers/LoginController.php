<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\LoginForm;
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

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            //$forbidden = $model->forbidden();
            $forbidden = false;
            if($forbidden){
//                return $this->render('locked',$forbidden);
            }else{
                /*if($model->preLogin()){
                    $model->login();
                    return $this->redirect(['/home/default/welcome']);
                }*/
            }
        }

        return $this->render('index',['model' => $model]);
    }
}
