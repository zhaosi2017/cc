<?php


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Manager */

$this->title = '创建管理员';
$this->params['breadcrumbs'][] = ['label' => 'Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
