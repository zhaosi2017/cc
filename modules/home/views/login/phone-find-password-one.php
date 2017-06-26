<?php

/* @var $model app\modules\home\models\RegisterForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '设置新密码';
?>
<div class=" text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>设置新密码</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'phone-password-complete',
            'options'=>['class'=>'form-horizontal m-t'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                'labelOptions' => [],
            ],
        ]); ?>


        <?= $form->field($model, 'password',[
            'template' => "<div> <div style=\"display:inline-block;width:91px;\">{label}</div>\n<div style=\"display:inline-block;width:215px;\">{input}</div> <span  style=\"display:inline-block;width:304px;line-height:13px;font-size:13px;\">*请输入账户新密码 ,密码至少包含8个字符，至少包括以下2种字符：大写字母、小写字母、数字、符号</span></div>\n<div class=\"row\">
        <div style=\"display:inline-block;width:91px;display:none;\">{label}</div>\n
        <div style=\"text-align:center;\"><span class=\"help-block m-b-none \" style=\"     
    margin-right: 157px;  \">{error}</span></div></div>",
        ])->passwordInput()->label('新密码：') ?>

        <?= $form->field($model, 'rePassword',[

            'template' => "<div> <div style=\"display:inline-block\">{label}</div>\n<div style=\"display:inline-block;width:215px;\">{input}</div> <span  style=\"display:inline-block;width:304px;  line-height:12px;\">*请输入账户新密码 ,密码至少包含8个字符，至少包括以下2种字符：大写字母、小写字母、数字、符号</span>\n<br/><div style=\"text-align:center;\"><span class=\"help-block m-b-none \" style=\" margin-right:187px;   \">{error}</span></div></div>",
        ])->passwordInput()->label('重复输入密码：') ?>

        <?= $form->field($model, 'phone')->hiddenInput()->label(false) ?>
        <?= $form->field($model,'country_code')->hiddenInput()->label(false)?>

        <?= Html::submitButton('完成', ['class' => 'btn btn-primary pull-center','style' =>'margin-right: 58px' ]) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



