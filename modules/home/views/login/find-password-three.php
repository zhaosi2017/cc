<?php

/* @var $model app\modules\home\models\RegisterForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '设置新密码';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>设置新密码</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-complete',
            'options'=>['class'=>'m-t text-left'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-9\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
            ],
        ]); ?>


        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rePassword')->passwordInput() ?>

        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>

        <?= Html::submitButton('完成', ['class' => 'btn btn-primary pull-right','style' =>'margin-right: 15px' ]) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



