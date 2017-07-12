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
<div class="text-center   animated fadeInDown">



        <div style="margin-top: 60px;width: 500px;">
          


            <!-- Tab panes -->


            <div role="tabpanel" class="tab-pane" id="profile">


                <?php $forms = ActiveForm::begin([

                    'id' => 'register-phone',
                    'options'=>['class'=>'m-t text-left'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                        'labelOptions' =>['style'=>'line-height:34px;'],
                        ],
                ]); ?>

                    <div class="row">
                        <div class="col-sm-6 " style="">
                            <?= $forms->field($model, 'country_code',[
                                'template' => "<div class=\"col-sm-6 text-right\">{label}</div>\n<div class=\"col-sm-6\" style=\"padding: 0px;\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                                ])->textInput([
                                'autofocus' => true,
                                'placeholder'=>Yii::t('app/user/update-phone-number','Country code'),
                                'size'=>5,

                            ])->label(Yii::t('app/user/update-phone-number','Phone')) ?>
                        </div>
                        <div  class="col-sm-6" style="display: inline-block;">
                            <?= $forms->field($model, 'phone',
                                [
                                    'template' => "{label}\n<div class=\"col-sm-12\" style=\"padding: 0px;\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",

                                ])->textInput([
                                'autofocus' => true,
                                'placeholder'=>Yii::t('app/user/update-phone-number','Phone'),
                            ])->label(false) ?>
                        </div>
                    </div>


                    <div class="row" style="margin-top:15px; ">
                        <div class="col-sm-6 " style="">
                            <?php echo $forms->field($model, 'code', [
//                                'template' => "{label}\n<div class='m-l-sm'>{input}\n
//                                                            <span style=\"height:28px;\" class=\"help-block m-b-none\">{error}</span>
//                                                        </div>",
                                'template' => "<div class=\"col-sm-6 text-right\">{label}</div>\n<div class=\"col-sm-6 \" style=\"padding: 0px\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",

                            ])->widget(Captcha::className(),[
                                'captchaAction'=>'/home/user/captcha',
                                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
                            ])
                                ->textInput(['size' => 5,'placeholder'=>Yii::t('app/user/update-phone-number' ,'Verify code')])
                                ->label(Yii::t('app/user/update-phone-number' ,'Verify code'))
                            ?>
                        </div>

                        <div class="col-sm-6 " style="">
<!--                            <div class="form-group" style="    ">-->
                                <input type="button" id="count-down" class="form-control"  onclick="
                                    if($('#phoneregisterform-country_code').val() == ''){
                                    alert('<?= Yii::t("app/user/update-phone-number", "Country Code is empty")?>');
                                    return false;
                                    }
                                    if($('#phoneregisterform-phone').val() == ''){
                                    alert('<?= Yii::t("app/user/update-phone-number", "Phone Number is empty")?>');
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
                                    $('#count-down').attr('disabled',false).val('<?= Yii::t("app/index" ,"Get code")?>');
                                    }
                                    };
                                    var dt = self.setInterval(countDown,1000);
                                    " value='<?= Yii::t("app/user/update-phone-number" ,"Get code")?>' style="background-color: #39b5e7;color: white;">
                                <div class="help-block"></div>
<!--                            </div>-->
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9" style="    padding-left: 0px;">
                        <input type="button" id="phoneregisterButton" class="btn btn-primary block full-width m-b button-new-color"
                               onclick="$('#register-phone').submit();"
                               value="<?= Yii::t('app/user/update-phone-number','Submit')?>" />
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                    <?php ActiveForm::end(); ?>

            </div>
    </div>
</div>


<style>
    /*#phoneregisterform-password{*/
        /*width: 314px !important;*/
    /*}*/
    /*#phoneregisterform-repassword{*/
        /*width: 314px !important;*/
    /*}*/

    /*#phoneregisterButton{*/
        /*width: 314px !important;*/
    /*}*/

    /*#count-down{*/
        /*margin-left: 8px !important;*/
    /*}*/
</style>