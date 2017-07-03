<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($isModify) {
    $this->title = Yii::t('app/user/add-urgent-contact-person' ,'Edit Contact Info');
} else {
    $this->title = Yii::t('app/user/add-urgent-contact-person' ,'Build Emergency Contact');
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


    <?php echo $form->field($model, 'contact_nickname',[
        'template' => "<div><div style=\"display：inline-block;\">{label}</div>\n<div class=\"\" style=\"display:inline-block;\">{input}</div><div style=\"display:inline-block;margin-left:10px;\">
                        <span >*".Yii::t('app/user/add-urgent-contact-person','Please enter the name of emergency contact.')."
                        <span></div>\n<div><span class=\"help-block m-b-none\" style=\"margin-left:17%;\">{error}</span></div>",
    ])->textInput(['placeholder' => Yii::t('app/user/add-urgent-contact-person','Emergency contact nickname')])
    ->label(Yii::t('app/user/add-urgent-contact-person','Name') ,['style'=>"    text-align: left;padding-left: 100px; padding-top:7px"])

    ?>

    <div class="row form-inline">



        <div class="col-sm-12">
           <!--  <div class="form-group">
                <div class="col-sm-1">
                    +
                    <div class="help-block"></div>
                </div>
            </div> -->
            <div class="col-sm-2 text-center" style="    font-weight: 600;">
                <?=  Yii::t('app/user/add-urgent-contact-person','Phone Number')?></label>

            </div>
            <?php echo $form->field($model, 'contact_country_code', [

                 'template' => "{label}\n<div style=\"width:130px;\">&nbsp;&nbsp;&nbsp;&nbsp;{input}\n
                                            <span style=\"height:18px;\" class=\"help-block m-b-none\">{error}</span>
                                         </div>",
            ])->textInput(['size' => 5,'placeholder'=>Yii::t('app/user/add-urgent-contact-person','Country Code'),])
             ->label(false) ?>

            <?php echo $form->field($model, 'contact_phone_number',[

                'template' => "<div>{label}\n<div>&nbsp;{input}<span style=\"margin-left:10px;\">*".
                    Yii::t('app/user/add-urgent-contact-person','Please enter the phone number of emergency contact.')

            ."</span></div>\n<span style=\"height:18px;\" class=\"help-block m-b-none\">{error}</span></div>",

            ])->textInput(['placeholder' => Yii::t('app/user/add-urgent-contact-person','Emergency contact phone'), 'size'=>'17'])->label(false) ?>
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


<?php echo '<style type="text/css">
    .has-error{
        margin-bottom: 0px;
    }
</style>';
?>