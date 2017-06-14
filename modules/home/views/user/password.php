<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label'=>'用户','url'=>''];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal m-t'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-1 '],
        ],
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'请输入原密码']) ?>

    <?= $form->field($model, 'newPassword')->passwordInput(['placeholder'=>'请输入新密码']) ?>

    <?= $form->field($model, 'rePassword')->passwordInput(['placeholder'=>'请重复输入密码']) ?>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
