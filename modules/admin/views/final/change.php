<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '帐变列表';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">
    <p class="btn-group hidden-xs">
        <?= Html::a('帐变列表', ['change'], ['class' => $actionId=='change' ? 'btn btn-primary' : 'btn btn-outline btn-default']) ?>
    </p>
    <div class="help-block m-t"></div>
    <?php  echo $this->render('_searchChange', ['model' => $searchModel]); ?>
    <div class="help-block m-t"></div>
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
            ['attribute'=>'user_id',
                'value'=>function($model){
                    return \app\modules\admin\models\User::findOne($model->user_id)->nickname;
                },
                'headerOptions'=>['class'=>'text-center']
            ],
            ['attribute'=>'change_type',
             'value'=>function($model){
                    return \app\modules\home\models\FinalChangeLog::$final_change_type[$model->change_type];
                },
             'headerOptions'=>['class'=>'text-center']
            ],
            ['attribute'=>'amount' ,  'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'comment', 'headerOptions'=>['class'=>'text-center']],
            [
                'attribute' => 'time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']
            ],
            ['attribute'=>'before',
                'value'=>function($model){
                    return $model->before;
                },
                'headerOptions'=>['class'=>'text-center']
            ],
            ['attribute'=>'after',
                'value'=>function($model){
                    return $model->after;
                },
                'headerOptions'=>['class'=>'text-center']
            ],


        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
