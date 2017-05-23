<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\LoginLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '登录日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-login-logs-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '序号'],

            ['header' => '管理员账号', 'value' => function($model){
                return $model['manager']['account'];
            }],

            ['header' => '管理员昵称', 'value' => function($model){
                return $model['manager']['nickname'];
            }],

            ['header' => '登录状态', 'value' => function($model){
                return $model->status ? $model['statuses'][$model->status] : '';
            }],

            ['header' => '登录IP', 'value' => function($model){
                return $model->login_ip;
            }],

            'login_time',

        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>
