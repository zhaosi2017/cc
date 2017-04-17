<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'auth_key',
            'password',
            'account:ntext',
            'nickname:ntext',
            // 'un_call_number',
            // 'un_call_by_same_number',
            // 'long_time:datetime',
            // 'phone_number',
            // 'urgent_contact_number_one',
            // 'urgent_contact_number_two',
            // 'urgent_contact_person_one:ntext',
            // 'urgent_contact_person_two:ntext',
            // 'telegram_number',
            // 'potato_number',
            // 'reg_time:datetime',
            // 'role_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
