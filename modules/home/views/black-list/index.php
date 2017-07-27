
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\BlackListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/harassment','Blacklist');
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<style>
    #content-main{
        overflow-y: scroll !important;
    }
</style>
<div class="call-record-index">
    <div class="help-block m-t"></div>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="help-block m-t"></div>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>".Yii::t('app/harassment','Total')."&nbsp;&nbsp;".$dataProvider->getTotalCount(). "&nbsp;".Yii::t('app/harassment','Data')." <span></li></ul>{pager}  </div>",
        // 'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
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

            /*
            ['header' => '编号', 'value' => function($model){
                return $model['id'];
            }],
            */



//            ['header' => Yii::t('app/harassment','Blacklist users'), 'value' => function($model){
//                return $model['black']['account'];
//            }, 'headerOptions'=>['class'=>'text-center']],
            //  ['header' => '联系电话', 'value' => function($model){
            //     return $model['black']['phone_number'];
            // }],
            ['header'=>'昵称' ,'value'=>function($model){
                return $model['black']['nickname'];
            },'headerOptions'=>['class'=>'text-center']],
            ['header' => 'telegram', 'value' => function($model){
                return !empty($model['black']['telegram_number'])?'+'.$model['black']['telegram_country_code'].$model['black']['telegram_number']:'';
            }, 'headerOptions'=>['class'=>'text-center']],

            [   'header'=>'telegram'.Yii::t('app/harassment',' Name') ,
                'value'=> function($model){return $model['black']['telegram_name'];} ,
                'headerOptions'=>['class'=>'text-center']],

            ['header' => 'potato', 'value' => function($model){
                return !empty($model['black']['telegram_number'])?'+'.$model['black']['potato_country_code'].$model['black']['potato_number']:'';
            }, 'headerOptions'=>['class'=>'text-center']],

            [     'header'=>'potato'.Yii::t('app/harassment',' Name') ,
                'value'=>function($model){return $model['black']['potato_name'];} ,
                'headerOptions'=>['class'=>'text-center']
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/harassment','Operating'),
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url){
                        return Html::a(Yii::t('app/harassment','Remove the blacklist'),$url,[
                            'style' => 'color:white',
                            'data-method' => 'post',
                            'class'=>'index-button-1',
                            'data' => ['confirm' => Yii::t('app/harassment','Are you sure you want to remove the blacklist?')]
                        ]);
                    },


                ],
                'headerOptions'=>['class'=>'text-center']
            ],

        ],
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
