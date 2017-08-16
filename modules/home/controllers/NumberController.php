<?php

namespace app\modules\home\controllers;

use app\modules\home\models\CallNumber;
use app\modules\home\models\FinalChangeLog;
use app\modules\home\models\FinalChangeSearch;
use app\modules\home\models\UserNumberSearch;
use app\modules\home\servers\FinalService\aiyi;
use app\modules\home\servers\FinalService\FinalService;
use Yii;
use app\modules\home\models\BlackListForm;
use app\modules\home\models\BlackList;
use app\modules\home\models\BlackListSearch;
use app\controllers\GController;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class NumberController extends GController
{


    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true,
                        'actions' => ['index', 'consume', 'pay','buy'],
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'buy' => ['get', 'post'],
                    'pay' => ['get', 'post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new CallNumber();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


    public function actionBuy($id)
    {
        $userid =  Yii::$app->user->id;
       

    }
}
