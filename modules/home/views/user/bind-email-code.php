<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = '登录';
?>
<div class="middle-box text-center   animated fadeInDown" style="margin-top: 0px !important;">
    <div>
        <div>



        </div>
        <h3>验证注册邮箱</h3>

        <blockquote class="text-left" style="border: 0;">
            我们已经向您的邮箱：<?php echo $model->username ?>发送了一封邮件,请填写邮箱中的验证码。
        </blockquote>
        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'bind-email-code',
            'options'=>['class'=>'m-t text-left'],
        ]); ?>

        <?= $form->field($model, 'username')->hiddenInput(['value' => isset($model->username) ? $model->username : ''])->label(false) ?>

        <?= $form->field($model, 'code')

            ->textInput(['autofocus' => true,'placeholder'=>'请输入验证码'])
            ->label(false)
        ?>

        <?= Html::submitButton('确定', ['class' => 'btn btn-primary block full-width m-b']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



