<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = '登录';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>验证注册邮箱</h3>

        <blockquote class="text-left" style="border: 0;">
            我们已经向您的注册邮箱：<?php echo $model->username ?>发送了一封邮件,请填写收到的验证码。
        </blockquote>
        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'code',
            'options'=>['class'=>'m-t text-left'],
        ]); ?>

        <?= $form->field($model, 'username')->hiddenInput(['value' => isset($model->username) ? $model->username : 'username'])->label(false) ?>

        <?= $form->field($model, 'password')->hiddenInput(['value' => isset($model->password) ? $model->password : 'password'])->label(false) ?>

        <?= $form->field($model, 'code')
            ->widget(Captcha::className(),[
            'captchaAction'=>'/home/register/captcha',
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
        ])
            ->textInput(['autofocus' => true,'placeholder'=>'请输入验证码'])
            ->label(false)
        ?>

        <?= Html::submitButton('确定', ['class' => 'btn btn-primary block full-width m-b button-new-color']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



