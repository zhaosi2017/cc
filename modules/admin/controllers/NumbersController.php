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