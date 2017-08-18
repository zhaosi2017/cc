<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/16
 * Time: 上午10:09
 */
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午9:53
 */
namespace app\modules\admin\controllers;
use app\modules\admin\models\Numbers\CallNumber;
use app\modules\admin\models\Numbers\UserNumber;
use Yii;

use app\controllers\PController;
use yii\filters\VerbFilter;

class NumbersController extends PController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * 查询平台号码列表
     */
    public function actionPlatform(){

        $searchModel = new CallNumber();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('platform', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 展示一个号码的详细信息
     *
     */
     public function actionShowNumber(){

        $id = Yii::$app->request->get();
        $number = CallNumber::findOne($id);
        if(empty($number)){
            $number = new CallNumber();
        }
        $number->begin_time = date('Y-m-d H:i:s' , $number->begin_time);
        $number->end_time = date('Y-m-d H:i:s' , $number->end_time);
        return $this->render('number',[
            'model'=> $number
        ]);
     }

    /**
     * 更新号码信息
     */
     public function actionModifyNumber(){

         $data = Yii::$app->request->post();
         $data = $data['CallNumber'];
         $model= \app\modules\home\models\CallNumber::findOne($data['id']);
         if(empty($model)){
            $model = new \app\modules\home\models\CallNumber();
         }
         $model->end_time = strtotime($data['end_time']);
         $model->begin_time = strtotime($data['begin_time']);
         $model->status = $data['status'];
         $model->rent_status = $data['rent_status'];
         $model->number =$data['number'];
         $model->comment= $data['comment'];
         $model->price= $data['price'];
         $model->interface= $data['interface'];
         if($model->save()){
             Yii::$app->session->setFlash("success", '操作成功');
         }else{
             Yii::$app->session->setFlash("error", '操作失败');
         }
         $this->redirect('platform');
     }


     public function actionDeleteNumber(){

        $id = Yii::$app->request->get('id');
        if(!empty($id)){
            $model = CallNumber::findOne($id);
            if($model->delete()){
                Yii::$app->session->setFlash("success", '操作成功');
            }else{
                Yii::$app->session->setFlash("error", '操作失败');
            }
        }else{
            Yii::$app->session->setFlash("error", '不存在的记录');
        }
         $this->redirect('platform');
     }

    /**
     * @return string
     * 查询用户的
     */
    public function actionUser(){
        $searchModel = new UserNumber();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


}