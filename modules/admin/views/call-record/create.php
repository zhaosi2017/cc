<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecord */

$this->title = 'Create Call Record';
$this->params['breadcrumbs'][] = ['label' => 'Call Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="call-record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
