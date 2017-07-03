<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = Yii::t('app/login','Verify email registration');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3><?= Yii::t('app/login','Verify email registration')?></h3>

        <blockquote class="text-left" style="border: 0;">
           <?= Yii::t('app/login','We have registered your email')?>：<?php echo $model->username ?><?= Yii::t('app/login','With you Sent a message Pease fill in the verification code received')?>。
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
            ->textInput(['autofocus' => true,'placeholder'=>Yii::t('app/login','Please input code')])
            ->label(false)
        ?>

        <?= Html::submitButton(Yii::t('app/login','Ok'), ['class' => 'btn btn-primary block full-width m-b button-new-color']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



