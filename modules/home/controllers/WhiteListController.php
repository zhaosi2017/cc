<?php

namespace app\modules\home\controllers;

use Yii;
use app\modules\home\models\WhiteListForm;
use app\modules\home\models\WhiteList;
 use app\modules\home\models\WhiteListSearch;
use app\controllers\GController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CallRecordController implements the CRUD actions for CallRecord model.
 */
class WhiteListController extends GController
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
		$searchModel = new WhiteListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	public function actionCreate()
	{
		$model = new WhiteListForm();
		if($model->load(Yii::$app->request->post()))
		{
			if($model->validate())
			{
				if ($model->createWhiteList()){
					Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
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
			Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
		}else{
			Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
		}
        return $this->redirect(['index']);
	}

	protected function findModel($id)
    {
        if (($model = WhiteList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
?>