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
                        'actions' => ['index', 'consume', 'pay', 'buy', 'sure-buy','user-number','sorting'],
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
                    'sure-buy' => ['post'],
                    'user-number'=>['get','post'],
                    'sorting'=>['get','post'],
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

        $userid = Yii::$app->user->id;
        $model = CallNumber::findOne($id);

        return $this->render('buy', ['model' => $model,'id'=>$id]);
    }

    public function actionSureBuy()
    {
        if (Yii::$app->request->isPost) {
            $id = $_POST['buyid'];
            $res = $this->checkBuy();
            if(empty($res))
            {
                return $this->redirect('buy?id='.$id)->send();
            }
            $userNumber = new UserNumber();
            if($userNumber->BuyNumber($res))
            {
                Yii::$app->session->setFlash('success','购买成功');
                return $this->redirect('user-number')->send();
            }

            return $this->redirect('buy?id='.$id)->send();
        }
    }

    private function checkBuy()
    {

        $number = intval($_POST['number']);
        $number_id = intval($_POST['callnumberid']);
        $_amount = $_POST['totalPrice'];

        $res = CallNumber::findOne(['id' => $number_id, 'number' => $number]);

        if (empty($res)) {
            return [];
        }
        $todayTime = strtotime(date('Y-m-d'));
        $begin_time = strtotime($_POST['begin_time']);
        $end_time = strtotime($_POST['end_time']);
        if ($begin_time < $res['begin_time'] || $begin_time > $res['end_time'] || $todayTime > $begin_time) {
            Yii::$app->session->setFlash('buy_begin_time', '开始时间选择有误');
            return [];
        }
        if ($end_time < $res['begin_time'] || $begin_time > $res['end_time']   || $todayTime > $end_time) {
            Yii::$app->session->setFlash('buy_begin_time', '结束时间选择有误');
            return [];
        }
        if ($begin_time > $end_time) {
            $tmp = $end_time;
            $end_time = $begin_time;
            $begin_time = $tmp;
        }

        $days = ( $end_time - $begin_time) / 86400;
        $days = $days < 1 ? 1 : $days;
        $amount = $days * $res->price;

        // $_amount == $amount
        return  [
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'number_id' => $number_id,
            'amount'=>$amount,
        ];
    }

    public function actionUserNumber()
    {
        $searchModel  = new UserNumberSearch();
        $dataProvider = $searchModel ->search((Yii::$app->request->queryParams));

        return $this->render('user-number', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSorting()
    {
        if(Yii::$app->request->isAjax)
        {
            $param = $_POST;
            $id = isset($param['id']) ? (int)$param['id'] : 0;
            $userId = (int)Yii::$app->user->id;
            $sorting = isset($param['sorting']) ? (int)$param['sorting'] : 0;

            if($id <= 0)
            {
                return json_encode(['status'=>0,'msg'=>'参数错误']);
            }
            $userNumber = UserNumber::find()->where(['id'=>$id,'user_id'=>$userId])->one();
            if(!empty($userNumber))
            {
                $userNumber->sorting = $sorting;
                if($userNumber->save()){
                    return json_encode(['status'=>1,'msg'=>'排序成功','sorting'=>$sorting]);
                }
            }
            return json_encode(['status'=>0,'msg'=>'参数错误']);
         }
    }
}
