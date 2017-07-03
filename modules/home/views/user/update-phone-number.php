<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title =  Yii::t('app/user/update-phone-number','Edit Phone number');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">



        <div style="margin-top: -60px;">
          
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" style="visibility: hidden !important;">


            </ul>

            <!-- Tab panes -->


            <div role="tabpanel" class="tab-pane" id="profile">


                <?php $forms = ActiveForm::begin([

                    'id' => 'register-phone',
                    'options'=>['class'=>'m-t text-left'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                    ],
                ]); ?>

                    <div class="row">
                        <div class="col-sm-6 " style="margin-left: -12px;">
                            <?= $forms->field($model, 'country_code')->textInput([
                                'autofocus' => true,
                                'placeholder'=>Yii::t('app/user/update-phone-number','Country code'),
                                'size'=>5,
                                'style'=>'width:125px;',
                            ])->label(false) ?>
                        </div>
                        <div  class="col-sm-6" style="display: inline-block;margin-left: 10px;">
                            <?= $forms->field($model, 'phone')->textInput([
                                'autofocus' => true,
                                'placeholder'=>Yii::t('app/user/update-phone-number','CellPhone Number'),
                            ])->label(false) ?>
                        </div>
                    </div>


                    <div class="row" style="margin-top:15px; ">
                        <div class="col-sm-6 " style="margin-left: -23px;">
                            <?php echo $forms->field($model, 'code', [
                                'template' => "{label}\n<div class='m-l-sm'>{input}\n
                                                            <span style=\"height:28px;\" class=\"help-block m-b-none\">{error}</span>
                                                        </div>",
                            ])->widget(Captcha::className(),[
                                'captchaAction'=>'/home/user/captcha',
                                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
                            ])
                                ->textInput(['size' => 18,'placeholder'=>Yii::t('app/user/update-phone-number' ,'verification code')])
                                ->label(false)
                            ?>
                        </div>
                        <div class="col-sm-6 " style="margin-left: 12px;">
                            <div class="form-group" style="    ">
                                <input type="button" id="count-down" class="form-control"  onclick="
                                    if($('#phoneregisterform-country_code').val() == ''){
                                    alert('<?= Yii::t("app/user/update-phone-number", "Country Code is empty")?>');
                                    return false;
                                    }
                                    if($('#phoneregisterform-phone').val() == ''){
                                    alert('<?= Yii::t("app/user/update-phone-number", "Cellphone Number is empty")?>');
                                    return false;
                                    }

                                    var duration = 59;
                                    $('#count-down').attr('disabled','disabled');
                                    var url = '<?php echo Url::to(['/home/user/send-short-message']); ?>';
                                    var data = {};

                                    data.number = '+' + $('#phoneregisterform-country_code').val() + $('#phoneregisterform-phone').val();
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
                                    $('#count-down').attr('disabled',false).val('<?= Yii::t("" ,"Get verification code")?>');
                                    }
                                    };
                                    var dt = self.setInterval(countDown,1000);
                                    " value='<?= Yii::t("app/user/update-phone-number" ,"Get verification code")?>' style="background-color: #39b5e7;color: white;">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <input type="button" id="phoneregisterButton" class="btn btn-primary block full-width m-b button-new-color"
                               onclick="$('#register-phone').submit();"
                               value="<?= Yii::t('app/user/update-phone-number','Submit')?>" />
                    </div>
                    <?php ActiveForm::end(); ?>

            </div>
    </div>
</div>


<style>
    #phoneregisterform-password{
        width: 314px !important;
    }
    #phoneregisterform-repassword{
        width: 314px !important;
    }

    #phoneregisterButton{
        width: 314px !important;
    }

    #count-down{
        margin-left: 8px !important;
    }
</style>