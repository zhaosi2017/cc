<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title = Yii::t('app/login','Forget password');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">&nbsp;</h1>
        </div>
        <h3><?= Yii::t('app/login','Forget password')?></h3>





        <div>

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
                            'placeholder'=>Yii::t('app/login','Country Code'),
                            'size'=>5,
                        ])->label(false) ?>
                    </div>
                    <div  class="col-sm-6" style="display: inline-block;margin-left: 10px;">
                        <?= $forms->field($model, 'phone')->textInput([
                            'autofocus' => true,
                            'placeholder'=>Yii::t('app/login','Phone number'),
                        ])->label(false) ?>
                    </div>




                    <div class="row" style="margin-top:73px; ">
                        <div class="col-sm-6 " style="margin-left: -12px;">
                            <?php echo $forms->field($model, 'code', [
                                'template' => "{label}\n<div class='m-l-sm'>{input}\n<span style=\"height:28px;\" class=\"help-block m-b-none\">{error}</span></div>",
                            ])->widget(Captcha::className(),[
                                'captchaAction'=>'/home/user/captcha',
                                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
                            ])
                                ->textInput(['size' => 18,'placeholder'=>Yii::t('app/login','Please input code')])
                                ->label(false)
                            ?>
                        </div>
                        <div class="col-sm-6 " style="margin-left: -12px;">
                            <div class="form-group" style="    ">
                                <input type="button" id="count-down" class=" btn  block full-width m-b button-new-color" style="color: white;" onclick="
                                        if($('#phoneregisterform-country_code').val() == ''){
                                        alert('<?php echo Yii::t("app/login","Country Code")?>');
                                        return false;
                                        }
                                        if($('#phoneregisterform-phone').val() == ''){
                                        alert('<?php echo Yii::t("app/login","Phone number")?>');
                                        return false;
                                        }

                                        var duration = 59;
                                        $('#count-down').attr('disabled','disabled');
                                        var url = '<?php echo Url::to(['/home/register/mobile-code']); ?>';
                                        var data = {};

                                        data.number = '+' + $('#phoneregisterform-country_code').val() + $('#phoneregisterform-phone').val();
                                        data.type   = '<?php echo Yii::$app->controller->action->id; ?>';
                                        $.post(url, data).done(function(r) {
                                        r = eval('('+ r + ')');
                                        if(r.messages.status == 1){
                                        alert('<?php echo Yii::t("app/login","Hello there! Send text messages too often, please take a break")?>');
                                        }
                                        });

                                        var countDown = function() {
                                        if(duration>0){
                                        $('#count-down').val(duration);
                                        duration--;
                                        }else{
                                        window.clearInterval(dt);
                                        $('#count-down').attr('disabled',false).val('<?php echo Yii::t("app/login","Get verification code")?>');
                                        }
                                        };
                                        var dt = self.setInterval(countDown,1000);
                                        " value='<?php echo Yii::t("app/login","Get verification code")?>'>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>




                    <input type="button" id="phoneregisterButton" class="btn btn-primary block full-width m-b button-new-color"
                           onclick="
//            var phonepatter = /^[0-9]{2,11}$/;
//
//            var phone = $('#phoneregisterform-phone').val().trim();
//
//
//            if( phone != ''
//                && phonepatter.test(phone)
//                ){
                $('#register-phone').submit();
//                $('#phoneregisterButton').attr('disabled','disabled');
//            }

                " value='<?php echo Yii::t("app/login","Next")?>'>

                    <?php ActiveForm::end(); ?>


                </div>

            </div>

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