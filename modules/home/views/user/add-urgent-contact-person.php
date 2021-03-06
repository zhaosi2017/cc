<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($isModify) {
    $this->title = Yii::t('app/user/add-urgent-contact-person' ,'Edit Contact Info');
} else {
    $this->title = Yii::t('app/user/add-urgent-contact-person' ,'Build Emergency Contact');
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
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


    <?php echo $form->field($model, 'contact_nickname',[
        'template' => "<div>
                            <div style=\"display：inline-block;\">{label}</div>\n
                            <div class=\"\" style=\"display:inline-block; margin-left: 10px;    width: 245px;\">{input}</div>
                            <div style=\"display:inline-block;margin-left:10px;\">
                                <span >*".Yii::t('app/user/add-urgent-contact-person','Please enter the name of emergency contact.')."
                                <span>
                            </div>\n
                            <div>
                                <span class='col-sm-2' ></span>
                                <span class=\"help-block m-b-none col-sm-4 \" style=\"margin-left:-5px;\">{error}</span>
                            </div>
                       </div>     ",
    ])->textInput(['placeholder' => Yii::t('app/user/add-urgent-contact-person','Emergency contact nickname')])
    ->label(Yii::t('app/user/add-urgent-contact-person','Name') ,['style'=>"text-align:right; padding-top:7px"])

    ?>

    <div class="row form-inline" style="margin-bottom: 15px">



        <div class="col-sm-12" style="margin-left: -15px">

            <div class="col-sm-2 text-right" style=" font-weight: 600;">
                   <label style="margin-top: 7px"> <?=  Yii::t('app/user/add-urgent-contact-person','Phone Number')?></label>
            </div>
            <div class="col-sm-1">
                <?php echo $form->field($model, 'contact_country_code', [

                     'template' => "{label}\n<div class='col-sm-12' >{input}\n
                                                <span  class=\"help-block m-b-none\">{error}</span>
                                             </div>",
                ])->textInput(['size' => 5,'placeholder'=>Yii::t('app/user/add-urgent-contact-person','Country Code'),])
                 ->label(false) ?>
            </div>
            <div class="col-sm-9" style="margin-left: -15px">
                <?php echo $form->field($model, 'contact_phone_number',[

                    'template' => "{label}\n
                                        <div class='col-sm-12'>&nbsp;{input}
                                            <span style=\"margin-left:15px;\">*".
                                                Yii::t('app/user/add-urgent-contact-person','Please enter the phone number of emergency contact.')

                                            ."</span>
                                            <span  class=\"help-block m-b-none \">{error}</span>
                                        </div>\n
                                        
                                   ",

                ])->textInput(['placeholder' => Yii::t('app/user/add-urgent-contact-person','Emergency contact phone'), 'size'=>'16'])->label(false) ?>
            </div>
        </div>

    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton($isModify ? Yii::t('app/user/add-urgent-contact-person' ,'Edit')
                                                    :Yii::t('app/user/add-urgent-contact-person' ,'Build') ,


                ['class' => 'btn btn-primary button-new-color','style'=>'width: 250px; margin-left: -6px;']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>


