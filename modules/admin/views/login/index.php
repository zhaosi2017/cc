<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

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

        <?= Html::submitButton('登 录', ['class' => 'btn btn-primary block full-width m-b']) ?>

        <?php ActiveForm::end(); ?>
        <p class="text-muted text-center">
            <a><small>若忘记密码请直接联系管理员</small></a>
        </p>
    </div>
</div>