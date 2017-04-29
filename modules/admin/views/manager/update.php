<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Manager */

$this->title = '修改管理员: ' . $model->account;
$this->params['breadcrumbs'][] = ['label' => 'Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="manager-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
