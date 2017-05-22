<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'account') ?>

    <?= $form->field($model, 'nickname') ?>

    <?php // echo $form->field($model, 'un_call_number') ?>

    <?php // echo $form->field($model, 'un_call_by_same_number') ?>

    <?php // echo $form->field($model, 'long_time') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'urgent_contact_number_one') ?>

    <?php // echo $form->field($model, 'urgent_contact_number_two') ?>

    <?php // echo $form->field($model, 'urgent_contact_person_one') ?>

    <?php // echo $form->field($model, 'urgent_contact_person_two') ?>

    <?php // echo $form->field($model, 'telegram_number') ?>

    <?php // echo $form->field($model, 'potato_number') ?>

    <?php // echo $form->field($model, 'reg_time') ?>

    <?php // echo $form->field($model, 'role_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
