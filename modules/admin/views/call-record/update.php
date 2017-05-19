<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecord */

$this->title = 'Update Call Record: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Call Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="call-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
