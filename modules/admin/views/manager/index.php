<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\ManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="manager-index">

    <p class="btn-group hidden-xs">
        <?= Html::a('管理员列表', ['index'], ['class' => $actionId=='trash' ? 'btn btn-outline btn-default' : 'btn btn-primary']) ?>
        <?= Html::a('垃圾筒', ['trash'], ['class' => $actionId=='index' ? 'btn btn-outline btn-default' : 'btn btn-primary']) ?>
    </p>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header' => '序号'],

            'account:ntext',
            'nickname:ntext',
            [
                'header' =>'角色',
                'value' => function($data){
                    return $data['role']['name'];
                },
            ],
            [
                'header' =>'状态',
                'value' => function($data){
                    switch ($data->status){
                        case 0:
                            return '正常';
                        case 1:
                            return '禁用';
                        case 2:
                            return '冻结';
                    }
                    return false;
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', //由于是默认类型，可以省略
                'header' => '最后修改人／时间',
                'format' => 'raw',
                'value' => function ($data) {
                    $account = $data['updater']['account'] ? $data['updater']['account'] : '系统';
                    return $account .'<br>'. date('Y-m-d H:i:s',$data->update_at);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', //由于是默认类型，可以省略
                'header' => '创建人／时间',
                'format' => 'raw',
                'value' => function ($data) {
                    $account = $data['creator']['account'] ? $data['creator']['account'] : '系统';
                    return $account.'<br>'.date('Y-m-d H:i:s',$data->create_at);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url){
                        return Html::a('编辑',$url);
                    },
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
    ]); ?>
<?php Pjax::end(); ?>
    <p class="text-right">
        <?= Html::a('创建管理员', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
    </p>
</div>
