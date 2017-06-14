
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\WhiteListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '白名单';
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
            // ['class' => 'yii\grid\SerialColumn', 'header' => '序号'],

            ['header' => '编号', 'value' => function($model){
                return $model['id'];
            }],

           
            ['header' => '白名单用户', 'value' => function($model){
                return $model['white']['account'];
            }],
             ['header' => '白名单电话', 'value' => function($model){
                return $model['white']['phone_number'];
            }],

           
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url){
                        return Html::a('删除',$url,[
                            'style' => 'color:red',
                            'data-method' => 'post',
                            'data' => ['confirm' => '你确定要删除吗?']
                        ]);
                    },
                ],
            ],

        ],
    ]);
    ?>
<?php Pjax::end(); ?>
 <p class="text-right">
        <?= Html::a('添加白名单', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
    </p>
</div>
