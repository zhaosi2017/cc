<?php

/* @var $model app\modules\home\models\RegisterForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app/login','Setting password');
?>
<div class=" text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3><?= Yii::t('app/login','Setting password')?></h3>

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
            'template' => "<div> <div style=\"display:inline-block;width:91px;\">{label}</div>\n<div style=\"display:inline-block;width:215px;\">{input}</div> <span  style=\"display:inline-block;width:304px;line-height:13px;font-size:13px;\">*". Yii::t('app/login','Please enter the account new password, the password contains at least 8 characters, including at least the following two characters: capital letters, lowercase letters, numbers, symbols')."</span></div>\n<div class=\"row\">
        <div style=\"display:inline-block;width:91px;display:none;\">{label}</div>\n
        <div style=\"text-align:center;\"><span class=\"help-block m-b-none \" style=\"     
    margin-right: 157px;  \">{error}</span></div></div>",
        ])->passwordInput()->label(Yii::t('app/login','New password').'：') ?>

        <?= $form->field($model, 'rePassword',[

            'template' => "<div> <div style=\"display:inline-block\">{label}</div>\n<div style=\"display:inline-block;width:215px;\">{input}</div> <span  style=\"display:inline-block;width:304px;  line-height:12px;\">*". Yii::t('app/login','Please enter the account new password, the password contains at least 8 characters, including at least the following two characters: capital letters, lowercase letters, numbers, symbols')."</span>\n<br/><div style=\"text-align:center;\"><span class=\"help-block m-b-none \" style=\" margin-right:187px;   \">{error}</span></div></div>",
        ])->passwordInput()->label(Yii::t('app/login','Repeat input password').'：') ?>

        <?= $form->field($model, 'phone')->hiddenInput()->label(false) ?>
        <?= $form->field($model,'country_code')->hiddenInput()->label(false)?>

        <?= Html::submitButton(Yii::t('app/login','Ok'), ['class' => 'btn btn-primary pull-center button-new-color','style' =>'margin-right: 58px' ]) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



