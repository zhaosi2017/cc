<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ManagerLoginLogs */

$this->title = 'Create Manager Login Logs';
$this->params['breadcrumbs'][] = ['label' => 'Manager Login Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-login-logs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
