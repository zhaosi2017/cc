<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\home\models\CallNumber;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =   Yii::t('app/nav','Callu number supermarket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Number store'), 'url' => ['number/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;


?>
<div class="call-record-index">

    <div class="help-block m-t"></div>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="help-block m-t"></div>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>&nbsp;".Yii::t('app/harassment','Total').'&nbsp;'.$dataProvider->getTotalCount(). '&nbsp;'.Yii::t('app/harassment','Data')." <span></li></ul>{pager}  </div>",
        'tableOptions'=>['class' => 'table table-striped table-bordered','style'=>'text-align:center;'],
        'pager'=>[
            'firstPageLabel'=>Yii::t('app/harassment','Frist'),
            'prevPageLabel'=>Yii::t('app/harassment','Previous'),
            'nextPageLabel'=>Yii::t('app/harassment','Next'),
            'lastPageLabel'=>Yii::t('app/harassment','Last page'),
            'maxButtonCount' => 9,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' =>  Yii::t('app/harassment','Serial number') , 'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'number',
                'value'=>function($model){
                    return $model->number;
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'status',
                'value'=>function($model){
                    $numStatus = CallNumber::getNumbStatus(); return $numStatus[$model->status];
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'rent_status',
                'value'=>function($model){
                    $rentStatus = CallNumber::getRentStatus(); return  $rentStatus[$model->rent_status];
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'price',
                'value'=>function($model){
                    return  $model->price;
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'end_time' ,
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']],
            ['attribute' => 'begin_time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/number/index','Operating'),
                'template' => '{buy}',
                'buttons' => [
                    'buy' => function($url){
                        return Html::a(Yii::t('app/number/index','Go to buy'),$url);
                    },

                ],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
