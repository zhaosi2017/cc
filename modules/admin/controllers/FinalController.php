<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午9:53
 */
namespace app\modules\admin\controllers;

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

}
