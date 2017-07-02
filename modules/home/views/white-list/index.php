
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\WhiteListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$userModels = \app\modules\home\models\User::findOne(Yii::$app->user->id);

$this->title = isset($userModels->whitelist_switch) && $userModels->whitelist_switch ? Yii::t('app/harassment','Whitelist switch').'：'.Yii::t('app/harassment','Open'): Yii::t('app/harassment','Whitelist switch').'：'.Yii::t('app/harassment','Closed');
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
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>".Yii::t('app/harassment','Total').'&nbsp;&nbsp;'.$dataProvider->getTotalCount().'&nbsp;&nbsp;'.Yii::t('app/harassment','Data') ." <span></li></ul>{pager}  </div>",
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
                ['class' => 'yii\grid\SerialColumn', 'header' => Yii::t('app/harassment','Serial number'), 'headerOptions'=>['class'=>'text-center']],

                /*
                ['header' => '编号', 'value' => function($model){
                    return $model['id'];
                }],
                */


           
            ['header' => Yii::t('app/harassment','Whitelist account'), 'value' => function($model){
                return $model['white']['account'];
            }, 'headerOptions'=>['class'=>'text-center']],
            //  ['header' => '联系电话', 'value' => function($model){
            //     return $model['white']['phone_number'];
            // }],
            ['header' => 'telegram', 'value' => function($model){
                return !empty($model['white']['telegram_number'])?'+'.$model['white']['telegram_country_code'].$model['white']['telegram_number']:'';
            }, 'headerOptions'=>['class'=>'text-center']],
            ['header' => 'potato', 'value' => function($model){
                return !empty($model['white']['potato_number'])?'+'.$model['white']['potato_country_code'].$model['white']['potato_number']:'';
            }],

           
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/harassment','Operating'),
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url){
                        return Html::a(Yii::t('app/harassment','Remove the whitelist'),$url,[
                            'style' => 'color:white',
                            'class'=>'index-button-1',
                            'data-method' => 'post',
                            'data' => ['confirm' => Yii::t('app/harassment','Are you sure you want to remove the whitelist?')]
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
