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
                   <?= Yii::t('app/user/set-phone-number' ,'CellPhone Number');?>
                </label>
            </div>
        </div>
        <div class="col-sm-10">

            <?php echo $form->field($model, 'country_code', [
                'template' => "{label}\n<div style=\"width:130px;\">&nbsp;&nbsp;&nbsp;{input}\n
                                            <span style=\"height:18px;\" class=\"help-block m-b-none\">{error}</span>
                                        </div>",
            ])->textInput(['size' => 10,'placeholder'=>Yii::t('app/user/set-phone-number' ,'Country code')])->label(false ) ?>

            <?php echo $form->field($model, 'phone_number',[

                 'template' => "{label}\n<div >&nbsp;{input}
                                            <span style=\"padding-left:10px\">*".
                                                Yii::t('app/user/set-phone-number' , 'Please enter your country code and enter your mobile number')
                                            ."</span>\n
                                            <span style=\"height:18px;\" class=\"help-block m-b-none\">{error}</span>
                                         </div>",

                ])->textInput(['placeholder' => Yii::t('app/user/set-phone-number' ,'CellPhone Number') ,'size' => 14,])->label(false) ?>
<!--            <div class="help-block">&nbsp;&nbsp;&nbsp;*请输入您的国码，然后输入您的手机号码</div>-->
        </div>

        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label" style="padding-top: 7px;">
                    <?= Yii::t('app/user/set-phone-number' ,'Verification code');?>
            </div>
        </div>
        <div class="col-sm-10">
            <!--<div class="form-group">
                &nbsp;&nbsp;
                <input title="" class="form-control" size="18" type="text" placeholder="填写验证码">
                <div class="help-block"></div>
            </div>-->

            <?php echo $form->field($model, 'code', [
                'template' => "{label}\n<div class='m-l-sm'>{input}\n<span style=\"height:28px;\" class=\"help-block m-b-none\">{error}</span></div>",
            ])->widget(Captcha::className(),[
                'captchaAction'=>'/home/user/captcha',
                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
            ])
                ->textInput(['size' => 18])
                ->label(false)
            ?>

            <div class="form-group">
                <input type="button" id="count-down" class="form-control"   onclick="
                    if($('#contactform-country_code').val() == ''){
                        alert('<?= Yii::t("app/user/update-phone-number", "Country Code is empty")?>');
                        return false;
                    }
                    if($('#contactform-phone_number').val() == ''){
                        alert('<?= Yii::t("app/user/update-phone-number", "Cellphone Number is empty")?>');
                        return false;
                    }

                    var duration = 59;
                    $('#count-down').attr('disabled','disabled');
                    var url = '<?php echo Url::to(['/home/user/send-short-message']); ?>';
                    var data = {};

                    data.number = '+' + $('#contactform-country_code').val() + $('#contactform-phone_number').val();
                    data.type   = '<?php echo Yii::$app->controller->action->id; ?>';
                    $.post(url, data).done(function(r) {
                        r = eval('('+ r + ')');
                        if(r.messages.status == 1){
                            alert('<?= Yii::t("app/user/update-phone-number","Send SMS too often, please take a break")?>');
                        }
                    });

                    var countDown = function() {
                        if(duration>0){
                            $('#count-down').val(duration);
                            duration--;
                        }else{
                            window.clearInterval(dt);
                            $('#count-down').attr('disabled',false).val(
                                                    '<?= Yii::t("app/user/update-phone-number" ,"Get verification code")?>');
                        }
                    };
                    var dt = self.setInterval(countDown,1000);
                " value='<?= Yii::t("app/user/update-phone-number" ,"Get verification code")?>'
                       style="background-color: #39b5e7;color: white;margin-top: -29px;"><span style="
    font-size: 14px;
    padding-left: 10px;
    /* padding-top: 0px; */
    position: relative;
    top: -12px;
">*<?= Yii::t("app/user/set-phone-number" , "Please enter your phone verification code")?></span>
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton(Yii::t('app/user/update-phone-number','Submit'), ['class' => 'btn btn-primary button-new-color','style'=>'width: 265px; margin-left: -6px;']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
