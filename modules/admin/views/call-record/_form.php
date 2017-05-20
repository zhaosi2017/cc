<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecord */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="call-record-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'active_call_uid')->textInput() ?>

    <?= $form->field($model, 'unactive_call_uid')->textInput() ?>

    <?= $form->field($model, 'call_by_same_times')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'call_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
