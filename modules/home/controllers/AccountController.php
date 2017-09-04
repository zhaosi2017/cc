<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/4
 * Time: 上午10:13
 */

namespace app\modules\home\controllers;

use app\modules\home\models\FinalChangeLog;
use Yii;
use app\modules\home\models\BlackListForm;
use app\modules\home\models\BlackList;
use app\modules\home\models\BlackListSearch;
use app\controllers\GController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class AccountController extends GController{


    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [	'allow' => true,
                        'actions' => ['recharge','index','consume'],
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'recharge' => ['get','post'],
                    'consume' =>['get' , 'post']
                ],
            ],
        ];
    }

    /**
     * 充值记录
     */
    public function actionRecharge(){
        $searchModel = new FinalChangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * 消费记录
     */
    public function actionConsume(){



    }









}