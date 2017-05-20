<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-harassment">
    <div class="user-form">

        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'form-horizontal m-t'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'account')->textInput(['readonly' => 'readonly'])->label('用户账号') ?>

        <?= $form->field($model, 'un_call_number')->textInput() ?>

        <?= $form->field($model, 'un_call_by_same_number')->textInput() ?>

        <?= $form->field($model, 'long_time')->textInput() ?>

        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? '创建' : '保存', ['class'=>'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

