<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '充值账号';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">
    <p class="btn-group hidden-xs">
        <?= Html::a('充值账号', ['recharge'], ['class' => $actionId=='recharge' ? 'btn btn-primary' : 'btn btn-outline btn-default']) ?>
    </p>

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
            ['attribute'=>'merchant_id',
                'value'=>function($model){
                    return $model->merchant_id;
                },
                'headerOptions'=>['class'=>'text-center']
            ],
            ['attribute'=>'recharge_type', 'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'amount', 'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'status' ,
                'value'=>function($model){
                    return \app\modules\admin\models\Finals\FinalMerchantInfo::$merchant_status_map[$model->status];
                },
                'headerOptions'=>['class'=>'text-center']],
            [
                'attribute' => 'time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']
            ]



        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
