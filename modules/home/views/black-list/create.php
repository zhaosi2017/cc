<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\home\models\WhiteList */

$this->title = '添加黑名单';
$this->params['breadcrumbs'][] = ['label' => '黑名单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
