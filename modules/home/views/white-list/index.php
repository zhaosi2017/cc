
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\WhiteListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$userModels = \app\modules\home\models\User::findOne(Yii::$app->user->id);

$this->title = isset($userModels->whitelist_switch) && $userModels->whitelist_switch ? '白名单开关：开':'白名单开关：关';
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
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>共".$dataProvider->getTotalCount(). "条数据 <span></li></ul>{pager}  </div>",
        // 'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
        'pager'=>[
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'末页',
            'maxButtonCount' => 9,
        ],
       
           'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header' => '序号'],

                /*
                ['header' => '编号', 'value' => function($model){
                    return $model['id'];
                }],
                */


           
            ['header' => '白名单用户', 'value' => function($model){
                return $model['white']['account'];
            }],
            //  ['header' => '联系电话', 'value' => function($model){
            //     return $model['white']['phone_number'];
            // }],
            ['header' => 'telegram', 'value' => function($model){
                return !empty($model['white']['telegram_number'])?'+'.$model['white']['telegram_country_code'].$model['white']['telegram_number']:'';
            }],
            ['header' => 'potato', 'value' => function($model){
                return !empty($model['white']['potato_number'])?'+'.$model['white']['potato_country_code'].$model['white']['potato_number']:'';
            }],

           
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url){
                        return Html::a('移出白名单',$url,[
                            'style' => 'color:red',
                            'data-method' => 'post',
                            'data' => ['confirm' => '你确定要移出白名单吗?']
                        ]);
                    },


            ], 
            ],

            ],
            ]);
    ?>
<?php Pjax::end(); ?>

</div>
