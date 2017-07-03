<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app/login','Retrieve login password');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3><?= Yii::t('app/login','Retrieve login password')?></h3>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-two',
            'options'=>['class'=>'m-t text-left'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-9\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                'labelOptions' => ['class' => 'col-sm-3 '],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput()->label(Yii::t('app/login','Email')) ?>

        <?= Html::submitButton(Yii::t('app/login','Next'), ['class' => 'btn btn-primary pull-right button-new-color','style' =>'margin-right: 15px']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



