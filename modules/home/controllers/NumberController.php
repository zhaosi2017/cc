<?php

namespace app\modules\home\controllers;

use app\modules\home\models\CallNumber;
use app\modules\home\models\FinalChangeLog;
use app\modules\home\models\FinalChangeSearch;
use app\modules\home\models\UserNumber;
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
                        'actions' => ['index', 'consume', 'pay','buy','sure-buy'],
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
                    'sure-buy'=>['post'],
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
        $model =  CallNumber::findOne($id);

        return $this->render('buy',['model'=>$model]);
    }

    public function actionSureBuy()
    {
        if(Yii::$app->request->isPost)
        {
            $number =  intval($_POST['number']);
            $callnumberid = intval($_POST['callnumberid']);
            $res = CallNumber::findOne(['id'=>$callnumberid,'number'=>$number]);
            if(empty($res))
            {
               return $this->redirect('index')->send();
            }
            $userid = Yii::$app->user->id ? Yii::$app->user->id:0;
            $userNumber = new UserNumber();
            $userNumber->user_id = $userid;
            $userNumber->time = time();
            $userNumber->begin_time = time();

        }
    }
}
