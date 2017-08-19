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
    <?php  echo $this->render('_search_user_number', ['model' => $searchModel]); ?>
    <div class="help-block m-t"></div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>".Yii::t('app/harassment','Total').'&nbsp;'.$dataProvider->getTotalCount()."&nbsp;".Yii::t('app/harassment','Data'). " <span></li></ul>{pager}  </div>",
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

            ['attribute'=>'number_id',
                'value'=>function($model){
                    $number = CallNumber::findOne([$model->number_id]);
                    return    $number->number;
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
            ['attribute' => 'sorting',
                'headerOptions'=>['class'=>'text-center'],

                'content'=>function($model){
                    return '<span id="sortContent'.$model->id.'">'.$model->sorting.'</span>';
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/number/index','Operating'),
                'headerOptions'=>['class'=>'text-center','style'=>'width:15%;'],
                'template' => '{buy}',
                'buttons' => [
                    'buy' => function($url,$model){
                        return  Html::activeInput('number',$model,'orderSort',['id'=>'ordersort'.$model->id,'min'=>0,'value'=>$model->sorting,'style'=>'width:50px !important;padding: 3px 6px;']).'&nbsp&nbsp<span class="btn index-button-1" style="color:white;" onclick="sortlist('.$model->id.')">'.Yii::t('app/number/index','Sorting'
                        ).'</span>';
                    },

                ],
            ],



        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>


<script>
    function sortlist(e) {
        var id = e;
        var ids = '#ordersort'+id;
        var sorting =  $(ids).val();
        var data = {};
        if( !(/^(\+|-)?\d+$/.test( sorting )) || sorting < 0){
            $(ids).val("0");
            sorting = 0;
        }
        data.id = id;
        data.sorting = sorting;
        $.post('/home/number/sorting',data,function (d) {
            var tt = eval('('+d+')');
            if(tt.status==1)
            {
                var sortContent = '#sortContent'+id;
                $(sortContent).html(tt.sorting);
            }else {
                alert('排序失败');
            }
        })
    }
</script>