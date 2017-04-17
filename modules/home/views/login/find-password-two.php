<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = '输入验证码';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>找回登录密码</h3>

        <blockquote class="text-left">
            我们已经向您的注册邮箱：<?php echo $model->username ?>发送了一封邮件,请填写收到的验证码。
        </blockquote>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-three',
            'options'=>['class'=>'m-t text-left'],
        ]); ?>

        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'code')
            ->widget(Captcha::className(),[
                'captchaAction'=>'/home/login/captcha',
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
            ])
            ->textInput(['autofocus' => true,'placeholder'=>'请输入验证码'])
            ->label(false)
        ?>

        <?= Html::submitButton('下一步', ['class' => 'btn btn-primary pull-right']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



