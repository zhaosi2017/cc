<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午9:53
 */
namespace app\modules\admin\controllers;

use app\modules\admin\models\Finals\FinalChangeLog;
use app\modules\admin\models\Finals\FinalMerchantInfo;
use app\modules\admin\models\Finals\FinalOrder;
use Yii;
use app\controllers\PController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class FinalController extends PController
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
     * 充值订单
     */
    public function actionOrder(){

        $searchModel = new FinalOrder();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 帐变列表
     */
    public function actionChange(){

        $searchModel = new FinalChangeLog();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('change', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }



    public function actionRecharge(){

        $searchModel = new FinalMerchantInfo();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('recharge', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 添加一个新的账号
     */
    public function actionMerchant(){

       $data =  Yii::$app->request->post();
       if(!empty($data['FinalMerchantInfo']['id']) ){    //新增
           $merchant = FinalMerchantInfo::findOne($data['FinalMerchantInfo']['id']);
       }
       if(empty($merchant)){
           $merchant  = new FinalMerchantInfo();
       }
        $merchant->status = $data['FinalMerchantInfo']['status'];
        $recharge = '';
        foreach ( $data['FinalMerchantInfo']['recharge_type'] as $v){
            $recharge += $v;
        }
        $merchant->recharge_type = (int)$recharge;
        $merchant->sign_type   = $data['FinalMerchantInfo']['sign_type'];
        $merchant->certificate = $data['FinalMerchantInfo']['certificate'];
        $merchant->merchant_id = $data['FinalMerchantInfo']['merchant_id'];
        $merchant->amount      = $data['FinalMerchantInfo']['amount'];
        $merchant->time        = time();
        if($merchant->save()) {
            Yii::$app->session->setFlash("success", '操作成功');
        }else{
            Yii::$app->session->setFlash("error", '操作失败');
        }
        return  $this->redirect('recharge');

    }

    /**
     * 显示一个账号的详细
     */
    public function actionShowMerchant(){

        $id = Yii::$app->request->get('id');
        $model = FinalMerchantInfo::findOne($id);
        if(empty($model)){
            $model =  new FinalMerchantInfo();
        }
        return $this->render('merchant' ,[
            'model'=>$model
        ]);
    }

    public function actionDeleteMerchant(){

        $id = Yii::$app->request->get('id');
        $model = FinalMerchantInfo::findOne($id);
        if(!empty($model)){
            $model->delete();
        }
        Yii::$app->session->setFlash("success", '操作成功');
        return $this->redirect('recharge');

    }

}
