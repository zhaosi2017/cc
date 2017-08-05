
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =   Yii::t('app/call-record/index','Account center  Personal call records');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<style>
    #content-main{
        overflow-y: scroll !important;
    }
    .pagination>.active>a{
        z-index: 0;
    }
</style>
<div class="call-record-index">
    <div class="help-block m-t"></div>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="help-block m-t"></div>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n  <div>
                                    <ul class='pagination'>
                                            <li style='display:inline;'>
                                                <span> ".Yii::t('app/call-record/index','Total').' '
                                                        .$dataProvider->getTotalCount()
                                                        .Yii::t('app/call-record/index','Data')." 
                                                <span>
                                            </li>
                                    </ul>{pager} 
                                </div>",
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
        'headerRowOptions'=>['class'=>'text-center'],
        'tableOptions'=>['class' => 'table table-striped table-bordered','style'=>'text-align:center;'],
        'pager'=>[
            'firstPageLabel'=>Yii::t('app/call-record/index','Frist'),
            'prevPageLabel'=>Yii::t('app/call-record/index','Previous'),
            'nextPageLabel'=>Yii::t('app/call-record/index','Next'),
            'lastPageLabel'=>Yii::t('app/call-record/index','Last page'),
            'maxButtonCount' => 9,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => Yii::t('app/call-record/index','Serial number'), 'headerOptions'=>['class'=>'text-center']],
            ['attribute' =>'active_account' , 'headerOptions'=>['class'=>'text-center']],
            ['attribute' =>'active_nickname' , 'headerOptions'=>['class'=>'text-center']],
            ['attribute' =>'unactive_account' , 'headerOptions'=>['class'=>'text-center']],
            ['attribute' =>'unactive_nickname' , 'headerOptions'=>['class'=>'text-center']],
            ['attribute' =>'typeData' , 'headerOptions'=>['class'=>'text-center']],
            ['attribute' =>'statusData' , 'headerOptions'=>['class'=>'text-center']],
            [
                'attribute' => 'call_time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']
            ],
        ],
    ]);
    ?>
<?php Pjax::end(); ?>
</div>
