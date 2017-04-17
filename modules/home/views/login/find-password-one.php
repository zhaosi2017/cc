<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '找回登录密码';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>找回登录密码</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-two',
            'options'=>['class'=>'m-t text-left'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-9\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput()->label('邮箱:') ?>

        <?= Html::submitButton('下一步', ['class' => 'btn btn-primary pull-right','style' =>'margin-right: 15px']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



