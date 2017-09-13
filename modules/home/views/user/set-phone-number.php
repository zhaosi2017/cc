<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

if ($isModify) {
    $this->title = Yii::t('app/user/set-phone-number' ,'Edit Phone number');
} else {
    $this->title = Yii::t('app/user/set-phone-number' ,'Build Phone number');
}
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-phone-number-form',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-10\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]); ?>

    <div class="row form-inline">
        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label" style="padding-top: 7px;">
                   <?= Yii::t('app/user/set-phone-number' ,'Phone Number');?>
                </label>
            </div>
        </div>
        <div class="col-sm-10">

            <?php echo $form->field($model, 'country_code', [
                'template' => "{label}\n<div>&nbsp;&nbsp;&nbsp;{input}\n
                                            <span style=\"height:18px;margin-left: 9px;width: 100px\" class=\"help-block m-b-none\">{error}</span>
                                        </div>",
                'options'=>['class'=>'form-group field-contactform-country_code required col-sm-2']
            ])->textInput(['size' => 8,'placeholder'=>Yii::t('app/user/set-phone-number' ,'Country code')])->label(false ) ?>

            <?php echo $form->field($model, 'phone_number',[

                 'template' => "{label}\n<div>&nbsp;{input}
                                            <span style=\"padding-left:12px;line-height: 34px \">*".
                                                Yii::t('app/user/set-phone-number' , 'Please enter your country code and enter your mobile number')
                                            ."</span>\n
                                            <span style=\"height:34px; width: 100px\" class=\"help-block m-b-none\">{error}</span>
                                        </div>",
                 'options'=>['class'=>'form-group field-contactform-phone_number required  ' ,'style'=>'margin-left: -50px;']
                ])->textInput(['placeholder' => Yii::t('app/user/set-phone-number' ,'Phone Number') ,
                                'size'=>13,
                                'class'=>'form-control col-sm-2'
                                ])
                ->label(false) ?>
<!--            <div class="help-block">&nbsp;&nbsp;&nbsp;*请输入您的国码，然后输入您的手机号码</div>-->
        </div>

        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label" style="padding-top: 7px;">
                    <?= Yii::t('app/index' ,'Get code');?>
            </div>
        </div>
        <div class="col-sm-10">

            <?php echo $form->field($model, 'code', [
                'template' => "{label}\n<div class='m-l-sm'>{input}\n
                                                <span style=\"height:34px;\" class=\"help-block m-b-none\">{error}</span>
                                       </div>",
                'options'=>['class'=>'form-group field-contactform-code required col-sm-2']
                ]
                )->widget(Captcha::className(),[
                'captchaAction'=>'/home/user/captcha',
                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
            ])
                ->textInput(['size' => 8    ])
                ->label(false)
            ?>

            <div class="form-group col-sm-10" style="margin-left: -50px">
                <div class="col-sm-2" style=" margin-left: -3px">
                    <input type="button" id="count-down" class="form-control"  onclick="
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').removeClass('has-error');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').removeClass('has-error');
                           $('#set-phone-number-form').find('.form-group.field-contactform-country_code').find('.help-block.m-b-none').text('');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').find('.help-block.m-b-none').text('');
                            if($('#contactform-country_code').val() == ''){
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').removeClass('has-success');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').addClass('has-error');
                            $('#contactform-country_code').attr('aria-invalid','false');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').find('.help-block.m-b-none').text('<?=Yii::t("app/login","Country code can not be empty")?>');

                            //alert('<?= Yii::t("app/user/update-phone-number", "Country Code is empty")?>');
                            return false;
                        }
                        if($('#contactform-phone_number').val() == ''){
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').removeClass('has-success');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').addClass('has-error');
                            $('#contactform-phone_number').attr('aria-invalid','false');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').find('.help-block.m-b-none').text('<?=Yii::t("app/login","The phone can not be empty")?>');

                            //alert('<?= Yii::t("app/user/update-phone-number", "Phone Number is empty")?>');
                            return false;
                        }


                            if( isNaN($('#contactform-country_code').val()) ||  $('#contactform-country_code').val().indexOf('+')==0)
                            {
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').removeClass('has-success');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').addClass('has-error');
                            $('#contactform-country_code').attr('aria-invalid','false');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').find('.help-block.m-b-none').text('<?=Yii::t("app/login","Country code number must be number")?>');

                            //alert('国码必须是数字');
                            return false;
                            }
                            if( isNaN($('#contactform-phone_number').val()) || $('#contactform-phone_number').val().indexOf('+')==0)
                            {
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').removeClass('has-success');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').addClass('has-error');
                            $('#contactform-phone_number').attr('aria-invalid','false');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').find('.help-block.m-b-none').text('<?= Yii::t("app/login","Phone number must be number")?>');



                            return false;
                            }


                            var duration = 59;
                        $('#count-down').attr('disabled','disabled');
                        var url = '<?php echo Url::to(['/home/user/send-short-message']); ?>';
                        var data = {};

                        data.number =   $('#contactform-country_code').val() + $('#contactform-phone_number').val();
                        data.type   = '<?php echo Yii::$app->controller->action->id; ?>';
                        $.post(url, data).done(function(r) {
                            r = eval('('+ r + ')');
                            if(r.messages.status == 1){
                            $('#count-down').attr('disabled','');
                            duration = 0;
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').removeClass('has-success');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').addClass('has-error');
                            $('#contactform-country_code').attr('aria-invalid','false');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').find('.help-block.m-b-none').text('<?= Yii::t("app/user/update-phone-number","Send SMS too often, please take a break")?>');

                                return false;
                            }
                            if(r.messages.status == 2){
                            duration = 0;
                            $('#count-down').attr('disabled','');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').removeClass('has-success');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').addClass('has-error');
                            $('#contactform-phone_number').attr('aria-invalid','false');
                            $('.form-group.field-contactform-phone_number').find('.help-block.m-b-none').text(r.messages.message);

                            return false;
                            }

                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').removeClass('has-error');
                            $('#contactform-phone_number').attr('aria-invalid','true');
                            $('#register-phone').find('.form-group.field-contactform-country_code').removeClass('has-error');
                            $('#contactform-country_code').attr('aria-invalid','true');
                            $('#set-phone-number-form').find('.form-group.field-contactform-country_code').find('.help-block.m-b-none').text('');
                            $('#set-phone-number-form').find('.form-group.field-contactform-phone_number').find('.help-block.m-b-none').text('');
                            });

                        var countDown = function() {
                            if(duration>0){
                                $('#count-down').val(duration);
                                duration--;
                            }else{
                                window.clearInterval(dt);
                                $('#count-down').attr('disabled',false).val(
                                                        '<?= Yii::t("app/index" ,"Get code")?>');
                            }
                        };
                        var dt = self.setInterval(countDown,1000);
                    " value='<?= Yii::t("app/index" ,"Get code")?>'
                     style="background-color: #39b5e7;color: white;margin-left: -27px; width: 100% ;padding-left: 0px" />
                </div>
                <div class="col-sm-7"  >
                    <span style=" line-height: 34px;margin-left: -34px">*<?= Yii::t("app/user/set-phone-number" , "Please enter your phone verification code")?></span>
                </div>
                <div class="help-block"></div>
            </div>

        </div>
        </div>
    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton(Yii::t('app/user/update-phone-number','Submit'), ['class' => 'btn btn-primary button-new-color','style'=>'width: 245px; margin-left: 10px;']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
