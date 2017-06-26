<?php

namespace app\modules\home\controllers;

use Yii;
use app\modules\home\models\BlackListForm;
use app\modules\home\models\BlackList;
use app\modules\home\models\BlackListSearch;
use app\controllers\GController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CallRecordController implements the CRUD actions for CallRecord model.
 */
class BlackListController extends GController
{
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [	'allow' => true,
                        'actions' => ['delete','index','create'],
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new BlackListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new BlackListForm();
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                if ($model->createBlackList()){
                    Yii::$app->getSession()->setFlash('success', '操作成功');
                    return $this->redirect(['index']);
                }
            }
            return $this->render('create',['model'=>$model]);
        }
        return $this->render('create',['model'=>$model]);

    }

    public function actionUpdate()
    {

    }

    public function actionDelete($id)
    {
        if($this->findModel($id)->delete())
        {
            Yii::$app->getSession()->setFlash('success', '操作成功');
        }else{
            Yii::$app->getSession()->setFlash('error', '操作失败');
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model =BlackList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
?>