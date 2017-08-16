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


$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">


    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>共".$dataProvider->getTotalCount(). "条数据 <span></li></ul>{pager}  </div>",
        'tableOptions'=>['class' => 'table table-striped table-bordered','style'=>'text-align:center;'],
        'pager'=>[
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'末页',
            'maxButtonCount' => 9,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '序号' , 'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'number',
                'value'=>function($model){
                    return $model->number;
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'status',
                'value'=>function($model){
                    return  CallNumber::$numStatusArr[$model->status];
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'rent_status',
                'value'=>function($model){
                    return  CallNumber::$numRentStatusArr [$model->rent_status];
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
                'header' => '操作',
                'template' => '{buy}',
                'buttons' => [
                    'buy' => function($url){
                        return Html::a('去购买',$url);
                    },

                ],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
