<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '注册';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">&nbsp;</h1>
        </div>
        <h3>注册个人账号</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
            'options'=>['class'=>'m-t text-left'],
            'fieldConfig' => [
                'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput([
            'autofocus' => true,
            'placeholder'=>'账号(仅支持邮箱：如xxx@gmail.com)',
        ])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码(至少8个字符,由大小写字母数字组合)'])->label(false) ?>

        <?= $form->field($model, 'rePassword')->passwordInput(['placeholder'=>'重复密码'])->label(false) ?>

        <?= Html::submitButton('注 册', ['class' => 'btn btn-primary block full-width m-b']) ?>

        <?php ActiveForm::end(); ?>
        <p class="text-muted text-center">
            <small>已经有账户了？</small><a href="<?php echo \yii\helpers\Url::to(['/home/login/index']) ?>">点此登录</a>
        </p>
    </div>
</div>