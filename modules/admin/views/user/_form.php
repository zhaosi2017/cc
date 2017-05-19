<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nickname')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'un_call_number')->textInput() ?>

    <?= $form->field($model, 'un_call_by_same_number')->textInput() ?>

    <?= $form->field($model, 'long_time')->textInput() ?>

    <?= $form->field($model, 'country_code')->textInput() ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'urgent_contact_number_one')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'urgent_contact_one_country_code')->textInput() ?>

    <?= $form->field($model, 'urgent_contact_number_two')->textInput() ?>

    <?= $form->field($model, 'urgent_contact_two_country_code')->textInput() ?>

    <?= $form->field($model, 'urgent_contact_person_one')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'urgent_contact_person_two')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'telegram_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telegram_country_code')->textInput() ?>

    <?= $form->field($model, 'potato_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'potato_country_code')->textInput() ?>

    <?= $form->field($model, 'reg_time')->textInput() ?>

    <?= $form->field($model, 'role_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
