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
<style>
    .bindpotato-img{
        height: 300px;
        width: 100%;
        border: 1px solid  #38b5e7;;
    }
    .bindpotato-div{
        /*width: 33.33333%;*/
    }
    #content-main{
        overflow-y: scroll !important;
    }
    .bindpotato-div-1{
        width: 100%;
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>
<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-phone-number-form',
        'options'=>['class'=>'form-horizontal m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2  text-right'],
        ],
    ]); ?>
     <div style="display: none;">
        <p style="margin-left: 160px;font-size: 13px;font-weight: 700;"><?=  Yii::t('app/telegram/bind-telegram','Steps') ?>：</p>
    </div>
    <div class="form-group" style="margin-left: 16.6%;display: none">
        <p>1、<?= Yii::t('app/telegram/bind-telegram' , 'Please log in to the personal account on telegram')?></p>
        <p>2、<?= Yii::t('app/telegram/bind-telegram','Add a robot friend')?>：<?php echo Yii::$app->params['telegram_name'];?></p>
        <p>3、<?= Yii::t('app/telegram/bind-telegram' , 'Share your business card to the robot')?></p>
        <p>4、<?= Yii::t('app/telegram/bind-telegram','Will get the verification code fill in the bottom of the input box for binding operation')?></p>
    </div>
    <input type="hidden" name="id" value="<?php if(isset($_GET['id']) && (int)$_GET['id']){echo (int)$_GET['id'];}else{echo 0;} ?>">
    <?php echo $form->field($model, 'bindCode',[
         'template' => "{label}\n<div class=\"col-sm-3\">{input}</div><span class='col-sm-5' style='margin-top:7px;'>

*".
             Yii::t('app/telegram/bind-telegram','Please enter the verification code you obtained from the telegram')
             ."</span><div class='col-sm-12'></div>\n
                <div class='col-sm-2'></div>
                <div class='col-sm-3'>
                <span class=\"help-block m-b-none\" >{error}</span>
                </div>
                <div class='col-sm-5'></div>
                </div>",
    ])->textInput(['placeholder' => Yii::t('app/telegram/bind-telegram','Code'),])
        ->label( Yii::t('app/telegram/bind-telegram','Code') ,['style'=>'padding-top: 9px;']) ?>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-3 " style="padding-left: 1px;">
            <?= Html::submitButton($isModify ? Yii::t('app/telegram/bind-telegram','Edit') :Yii::t('app/telegram/bind-telegram','Build') ,
                ['class' => 'btn btn-primary button-new-color','style'=>'width: 100%;']) ?>
        </div>
        <div class="col-sm-5"></div>
    </div>

    <?php ActiveForm::end(); ?>


</div>

<div class="col-sm-12"></div>
<div class="row">
    <p class="col-sm-12" style="margin-left: 17px;font-weight: 700;"><?=  Yii::t('app/potato/bind-potato','Steps') ?>：</p>
</div>
<div class="row">
    <div class="col-sm-2 bindpotato-div">
        <img class="img-responsive center-block bindpotato-img" src="/img/telegram/telegram-index-1.jpg" alt="">
        <div class="text-center">(1)</div>
        <div class="bindpotato-div-1 text-center"><?= Yii::t('app/index','After logging in to Telegram, go to the "Chats" page.')?></div>
    </div>
    <div class="col-sm-2 bindpotato-div">
        <img class="img-responsive center-block bindpotato-img" src="/img/telegram/telegram-index-2.jpg" alt="">
        <div class="text-center">(2)</div>
        <div class="bindpotato-div-1 text-center"><?= Yii::t('app/index','Enter "callu_bot" in the search bar on the "Chats" page and perform the search. Click "callu" to enter Figure 3.')?></div>
    </div>
    <div class="col-sm-2 bindpotato-div">
        <img class="img-responsive center-block bindpotato-img" src="/img/telegram/telegram-index-3.jpg" alt="">
        <div class="text-center">(3)</div>
        <div class="bindpotato-div-1 text-center"><?= Yii::t('app/index','Click the "Start" button to enter Figure 4.')?></div>
    </div>
    <div class="col-sm-2 bindpotato-div">
        <img class="img-responsive center-block bindpotato-img" src="/img/telegram/telegram-index-4.jpg" alt="">
        <div class="text-center">(4)</div>
        <div class="bindpotato-div-1 text-center"><?= Yii::t('app/index','Click "Share your own business card" to enter Figure 5.')?></div>
    </div>
    <div class="col-sm-2 bindpotato-div">
        <img class="img-responsive center-block bindpotato-img" src="/img/telegram/telegram-index-5.jpg" alt="">
        <div class="text-center">(5)</div>
        <div class="bindpotato-div-1 text-center"><?= Yii::t('app/index','In the "Share Your Phone Number?" Box select "OK", enter Figure 6.')?></div>
    </div>
    <div class="col-sm-2 bindpotato-div">
        <img class="img-responsive center-block bindpotato-img" src="/img/telegram/telegram-index-6.jpg" alt="">
        <div class="text-center">(6)</div>
        <div class="bindpotato-div-1 text-center"><?= Yii::t('app/index','Will fill in the verification code to fill in the "verification code" input box, the binding.')?></div>
    </div>
</div>


