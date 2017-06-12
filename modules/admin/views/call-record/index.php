<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '呼叫记录';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">
    <p class="btn-group hidden-xs">
        <?= Html::a('呼叫记录', ['index'], ['class' => $actionId=='index' ? 'btn btn-primary' : 'btn btn-outline btn-default']) ?>
        <!--  
        // Html::a('黑名单', ['blacklist'], ['class' => $actionId=='blacklist' ? 'btn btn-primary' : 'btn btn-outline btn-default']) 

        // Html::a('垃圾筒', ['trash'], ['class' => $actionId=='trash' ? 'btn btn-primary' : 'btn btn-outline btn-default']) 
           -->
    </p>
    <div class="help-block m-t"></div>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="help-block m-t"></div>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            return ['id' => 'tr_'.$model->id, 'class' => '_tr'];
        },
        'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>共有".$dataProvider->getTotalCount(). "条数据 <span></li></ul>{pager}  </div>",

        'pager'=>[
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'末页',
            'maxButtonCount' => 9,
        ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                // 'footer' =>'<a href="javascript:;" class="_delete_all" data-url="'.Url::to(['/user/delete-all']).'"></a>',
                // 'footerOptions' => ['colspan' => 13],
            ],
            'id',
            'active_account',
            'active_nickname',
            'contact_number',
            'unactive_account',
            'unactive_nickname',
            'call_by_same_times',
            'type',
            'unactive_contact_number',
            'status',
            [
                'attribute' => 'call_time',
                'format'=>['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
            ],
        ],
    ]);
    ?>
<?php Pjax::end(); ?>
</div>
