<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
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
        <h3>登录</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
            'options'=>['class'=>'m-t text-left'],
            'fieldConfig' => [
                'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput([
            'autofocus' => true,
            'placeholder'=>'邮箱账号',
        ])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码'])->label(false) ?>
        <?= $form->field($model, 'code')->textInput(['placeholder'=>'请输入验证码'])->label(false) ?>
        <?php echo Captcha::widget(['name'=>'captchaimg','captchaAction'=>'captcha','imageOptions'=>['id'=>'captchaimg', 'title'=>'换一个', 'alt'=>'换一个', 'style'=>'cursor:pointer;margin-top:10px; height: 40px;margin-bottom:10px;'],'template'=>'{image}']); ?>

        <?= Html::submitButton('登 录', ['class' => 'btn btn-primary block full-width m-b']) ?>

        <?php ActiveForm::end(); ?>
        <p class="text-muted text-center">
            <a href="<?php echo \yii\helpers\Url::to(['/home/login/find-password-one']) ?>"><small>忘记密码了？</small></a> | <a href="<?php echo \yii\helpers\Url::to(['/home/register/index']) ?>">注册一个新账号</a>
        </p>
    </div>
</div>