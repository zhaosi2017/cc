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
<style>

</style>
<div class=" text-center loginscreen  animated fadeInDown">
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
                        'labelOptions'=>['class'=>'col-sm-8 text-right' ,'style'=>'line-height:34px;    position: relative;
    left: 30px;'],
                    ],
                ]); ?>

                <div class="row">
                    <div class="col-sm-6 " style="">
                        <?= $forms->field($model, 'country_code',
                            [
                                'template' => "{label}\n<div class=\"col-sm-3\" style=\"margin-left: 30px\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                                ])->textInput([
                            'autofocus' => true,
                            'placeholder'=>Yii::t('app/login','Country Code'),
                            'size'=>8,
                        ])->label(Yii::t('app/login','Phone')) ?>
                    </div>
                    <div  class="col-sm-6" style=" padding-left: 0px;">
                        <div class="col-sm-4" >
                        <?= $forms->field($model, 'phone')->textInput([
                            'autofocus' => true,
                            'placeholder'=>Yii::t('app/login','Phone'),
                            'size'=>'8',

                        ])->label(false) ?>
                        </div>
                        <div class="col-sm-8">
                        </div>
                    </div>
                </div>


                    <div class="col-sm-12"></div>

                    <div class="row" style=" ">
                        <div class="col-sm-6 " style="">
                            <?php echo $forms->field($model, 'code', [
                                'template' => "{label}\n<div class=\"col-sm-3\" style=\"margin-left: 30px;\">{input}\n<span style=\"height:28px;\" class=\"help-block m-b-none\">{error}</span></div>",
                            ])->widget(Captcha::className(),[
                                'captchaAction'=>'/home/user/captcha',
                                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
                            ])
                                ->textInput(['size' => 8,'placeholder'=>Yii::t('app/login','Verification code')])
                                ->label(Yii::t('app/login','Verification code'))
                            ?>
                        </div>
                        <div class="col-sm-6 " style="">

                                <div class="col-sm-4" style="position: relative;right: 10px;">
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
                                </div>
                                <div class=" col-sm-8 help-block"></div>
                            </div>

                    </div>



                    <div >
                       <div class="col-sm-4"></div>
                        <div class="col-sm-4" style="    padding-left: 34px;
    padding-right: 11px;">
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
                        <div class="col-sm-4"></div>
                    </div>


                </div>

            </div>

        </div>








    </div>
</div>


<style>
 #phoneregisterButton{
     margin-top: 20px;
 }
</style>