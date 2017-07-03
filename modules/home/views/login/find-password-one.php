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
                'labelOptions' => ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px;'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput()->label(Yii::t('app/login','Email')) ?>

        <div class="form-group">
            <label class="col-sm-3"></label>
            <div class = "col-sm-9">
            <?= Html::submitButton(Yii::t('app/login','Next'), ['class' => ' btn btn-primary pull-right button-new-color','style' =>'width:100%;']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>



