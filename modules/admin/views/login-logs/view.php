<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ManagerLoginLogs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Manager Login Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-login-logs-view">

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
            'uid',
            'status',
            'login_time',
            'login_ip',
        ],
    ]) ?>

</div>