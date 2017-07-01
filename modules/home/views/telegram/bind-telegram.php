<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($isModify) {
    $this->title =  Yii::t('app/telegram/bind-telegram','Edit telegram account');
} else {
    $this->title = Yii::t('app/telegram/bind-telegram','Bind telegram account');
}
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-phone-number-form',
        'options'=>['class'=>'form-horizontal m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2  text-right'],
        ],
    ]); ?>
     <div>
        <p style="margin-left: 160px;font-size: 13px;font-weight: 700;"><?=  Yii::t('app/telegram/bind-telegram','Steps') ?>：</p>
    </div>
    <div class="form-group" style="margin-left: 16.6%;">
        <p>1、<?= Yii::t('app/telegram/bind-telegram' , 'Please log in to the personal account on telegram')?></p>
        <p>2、<?= Yii::t('app/telegram/bind-telegram','Add a robot friend')?>：<?php echo Yii::$app->params['telegram_name'];?></p>
        <p>3、<?= Yii::t('app/telegram/bind-telegram' , 'Share your business card to the robot')?></p>
        <p>4、 <?= Yii::t('app/telegram/bind-telegram' , 'Select the binding operation')?></p>
        <p>5、<?= Yii::t('app/telegram/bind-telegram','Will get the verification code fill in the bottom of the input box for binding operation')?></p>
    </div>

    <?php echo $form->field($model, 'bindCode',[
         'template' => "{label}\n<div class=\"col-sm-3\">{input}</div><span>

*".
             Yii::t('app/telegram/bind-telegram','Please enter the verification code you obtained from the telegram')
             ."</span>\n<span class=\"help-block m-b-none\" style=\"margin-top:17px;margin-left:17.5%;\">{error}</span></div>",
    ])->textInput(['placeholder' => Yii::t('app/telegram/bind-telegram','Code'),])
        ->label( Yii::t('app/telegram/bind-telegram','Code') ,['style'=>'padding-top: 9px;']) ?>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton($isModify ? Yii::t('app/telegram/bind-telegram','Edit') :Yii::t('app/telegram/bind-telegram','Build') ,


                ['class' => 'btn btn-primary button-new-color','style'=>'width: 325px; margin-left: -10px;']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
