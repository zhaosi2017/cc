<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/4
 * Time: 上午10:13
 */

namespace app\modules\home\controllers;

use app\modules\home\models\FinalChangeLog;
use app\modules\home\models\FinalChangeSearch;
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


class AccountController extends GController{


    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [	'allow' => true,
                        'actions' => ['recharge','index','consume','pay'],
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'recharge' => ['get','post'],
                    'consume' =>['get' , 'post'],
                    'pay' =>['get','post'],
                ],
            ],
        ];
    }

    /**
     * 充值记录
     */
    public function actionRecharge(){

        $param =   [
                    'start_time' =>'',
                    'end_time' => '',
                    'change_type'=>0
                   ];

        $get = Yii::$app->request->get();
        if(isset($get['FinalChangeSearch'])){
            $param = $get['FinalChangeSearch'];
        }
        $searchModel = new FinalChangeSearch();
        $data = $searchModel->ApiSearch($param);
        $pagination = new Pagination([
            'totalCount'=>$data->count(),
           // 'pageSize'=>1,
        ]);

        $model = $data->orderBy('id ASC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index',
            [
                'model'=>$model,
                'pagination'=>$pagination,
                'searchModel'=>$searchModel,
                'param'=>$param
            ]
        );
    }

    /**
     * 消费记录
     */
    public function actionConsume(){



    }

    public function actionPay()
    {
        $type = aiyi::$service_map;
        if(Yii::$app->request->isPost)
        {


            $order_type =isset($_POST['order_type']) ? $_POST['order_type']:0;
            if(!array_key_exists($order_type,aiyi::$service_map))
            {

                return $this->render('pay',['type'=>$type]);
            }
            $amount = isset($_POST['amount']) ? $_POST['amount']: 0;
            if( !is_numeric($amount) || $amount<=0)
            {

                return $this->render('pay',['type'=>$type]);
            }

            $server = new FinalService();
            $res = $server->CreateOrder($order_type,$amount);
            var_dump($res);die;
        }

        return $this->render('pay',['type'=>$type]);
    }









}