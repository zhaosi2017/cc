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
        <h3 ><?= Yii::t('app/login','Setting password')?></h3>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-complete',
            'options'=>['class'=>'form-horizontal m-t'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class'=>'text-right'],
        ],
        ]); ?>




        <?= $form->field($model, 'password',[
            'template' => "<div class=\"col-sm-3 text-right\"><span style='line-height: 34px;'>{label}</span></div>\n<div class=\"col-sm-3\">{input}\n</div> <div class='col-sm-5 text-left' style='line-height: 17px;'>".Yii::t('app/login','Password contains at least 8 characters, including at least the following two characters: capital letters, lowercase letters, numbers, symbols')."</div>\n<div class='col-sm-12'></div><div class='col-sm-3'></div><div class='col-sm-3 text-left'> <span class=\"help-block m-b-none\">{error}</span></div><div class='col-sm-6'></div>",
        ])->passwordInput()->label(Yii::t('app/login','New password').':') ?>


        <?= $form->field($model, 'rePassword',[
            'template' => "<div class=\"col-sm-3 text-right\"><span style='line-height: 34px;'>{label}</span></div>\n<div class=\"col-sm-3\">{input}\n</div> <div class='col-sm-5 text-left' style='line-height: 17px;'>".Yii::t('app/login','Password contains at least 8 characters, including at least the following two characters: capital letters, lowercase letters, numbers, symbols')."</div>\n<div class='col-sm-12'></div><div class='col-sm-3'></div><div class='col-sm-3 text-left'> <span class=\"help-block m-b-none\">{error}</span></div><div class='col-sm-6'></div>",
        ])->passwordInput()->label(Yii::t('app/login','New password').':') ?>



        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>

        <div class="form-group">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-3">
                <?= Html::submitButton(Yii::t('app/login','Ok'), ['class' => 'btn btn-primary pull-center button-new-color','style' =>'width:100%;' ]) ?>

            </div>
            <div class="col-sm-6"></div>
         </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>



