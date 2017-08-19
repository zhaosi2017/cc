<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '平台号码管理';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">
    <p class="btn-group hidden-xs">
        <?= Html::a('添加号码', ['show-number'], ['class' => $actionId=='platform' ? 'btn btn-primary' : 'btn btn-outline btn-default']) ?>
    </p>
    <div class="help-block m-t"></div>
    <?php  echo $this->render('_searchPlatform', ['model' => $searchModel]); ?>
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
            ['attribute'=>'number', 'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'status',
                'value'=>function($model){
                    return \app\modules\home\models\CallNumber::$numStatusArr[$model->status];
                },

                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'comment' , 'headerOptions'=>['class'=>'text-center']],
            [
                'attribute' => 'rent_status',
                'value'=>function($model){
                    return \app\modules\home\models\CallNumber::$numRentStatusArr[$model->rent_status];
                },
                'headerOptions'=>['class'=>'text-center']
            ],
            ['attribute'=>'begin_time' ,
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'end_time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'price' ,
                'value'=>function($model){
                    return $model->price.'$';
                },
                'headerOptions'=>['class'=>'text-center']],
            ['attribute'=>'interface' , 'headerOptions'=>['class'=>'text-center']],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{show-number} {delete-number}',
                'buttons' => [
                    'show-number' => function($url){
                        return Html::a('编辑',$url);
                    },
                    'delete-number' => function($url){

                        if(Yii::$app->user->can('admin/numbers/delete-number')){

                            return Html::a('删除',$url,[
                                'style' => 'color:red',
                                'data' => ['confirm' => '你确定要删除吗?']
                            ]);
                        }else{
                            $url = 'index';
                            return Html::a('删除',$url,[
                                'style' => 'color:red',
                                'data' => ['confirm' => '您没有该权限！']
                            ]);

                        }


                    },
                ],
            ],



        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
