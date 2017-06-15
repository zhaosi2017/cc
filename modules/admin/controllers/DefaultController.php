<?php

namespace app\modules\admin\controllers;
use Yii;
use app\controllers\PController;
use app\modules\admin\models\PasswordForm;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends PController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionDeny(){
    	return $this->render('deny');
    }

    public function actionPassword()
    {
        $model = new PasswordForm();
        if($model->load(Yii::$app->request->post()) ){
            if($res = $model->updateSave()){
                Yii::$app->getSession()->setFlash('success', '密码修改成功');
                return $this->redirect(['index'])->send();
            }else{
                Yii::$app->getSession()->setFlash('error', '密码修改失败');
                return $this->render('password',['model' => $model]);
            }
            
        }   
        return $this->render('password',['model' => $model]);
    }
}
