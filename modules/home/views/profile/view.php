<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'auth_key',
            'password',
            'account:ntext',
            'nickname:ntext',
            'un_call_number',
            'un_call_by_same_number',
            'long_time:datetime',
            'phone_number',
            'urgent_contact_number_one',
            'urgent_contact_number_two',
            'urgent_contact_person_one:ntext',
            'urgent_contact_person_two:ntext',
            'telegram_number',
            'potato_number',
            'reg_time:datetime',
            'role_id',
        ],
    ]) ?>

</div>
