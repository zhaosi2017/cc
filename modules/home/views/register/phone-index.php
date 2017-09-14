<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title = Yii::t('app/login','Register');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">&nbsp;</h1>
        </div>
        <h3><?= Yii::t('app/login','Register a personal account')?></h3>





        <div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a href="/home/register/register" ><?= Yii::t('app/login','Email')?></a></li>
                <li role="presentation" class="active"><a href="#" ><?= Yii::t('app/login','Phone')?></a></li>

            </ul>

            <!-- Tab panes -->
          

                <div role="tabpanel" class="tab-pane" id="profile">


                    <?php $forms = ActiveForm::begin([
                        'action' => ['phone-index'],
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

                    <?= $forms->field($model, 'password')->passwordInput(['placeholder'=>Yii::t('app/login','Password  least 8  upper & lower char')])->label(false) ?>

                    <?= $forms->field($model, 'rePassword')->passwordInput(['placeholder'=>Yii::t('app/login','Repeat password')])->label(false) ?>



                    <div class="row">
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
                <input type="button" id="count-down" class="form-control"  style="background-color: #39b5e7;color: white;" onclick="
                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').removeClass('has-error');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').removeClass('has-error');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').find('.help-block.help-block-error').text('');
                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').find('.help-block.help-block-error').text('');
                        $('#error_mes_p').text('');

                   if($('#phoneregisterform-country_code').val() == ''){

                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').removeClass('has-success');
                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').addClass('has-error');
                        $('#phoneregisterform-country_code').attr('aria-invalid','false');
                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').find('.help-block.help-block-error').text('<?=Yii::t("app/login","Country code can not be empty")?>');

                        return false;
                    }
                        if( isNaN($('#phoneregisterform-country_code').val()) || $('#phoneregisterform-country_code').val().indexOf('+')==0)
                        {

                        var _contry_code =  $('#register-phone').find('.form-group.field-phoneregisterform-country_code');
                        if( _contry_code.hasClass('has-success') || _contry_code.hasClass('has-error') ||(!_contry_code.hasClass('has-success') &&  !_contry_code.hasClass('has-error')) )
                        {
                            $('#register-phone').find('.form-group.field-phoneregisterform-country_code').removeClass('has-success');
                            $('#register-phone').find('.form-group.field-phoneregisterform-country_code').addClass('has-error');
                            $('#phoneregisterform-country_code').attr('aria-invalid','false');
                            $('#register-phone').find('.form-group.field-phoneregisterform-country_code').find('.help-block.help-block-error').text('<?=Yii::t("app/login","Country code number must be number")?>');

                        }

                        return false;
                        }

                    if($('#phoneregisterform-phone').val() == ''){
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').removeClass('has-success');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').addClass('has-error');
                        $('#phoneregisterform-phone').attr('aria-invalid','false');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').find('.help-block.help-block-error').text('<?=Yii::t("app/login","The phone can not be empty")?>');

                        return false;
                    }
                        if( isNaN($('#phoneregisterform-phone').val()) ||   $('#phoneregisterform-phone').val().indexOf('+')==0)
                        {
                        var _phone =  $('#register-phone').find('.form-group.field-phoneregisterform-country_code');
                        if( _phone.hasClass('has-success') || _phone.hasClass('has-error') ||(!_phone.hasClass('has-success') &&  !_phone.hasClass('has-error')) )
                        {
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').removeClass('has-success');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').addClass('has-error');
                        $('#phoneregisterform-phone').attr('aria-invalid','false');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').find('.help-block.help-block-error').text('<?= Yii::t("app/login","Phone number must be number")?>');

                        }
                        return false;
                        }

                    var duration = 59;
                    $('#count-down').attr('disabled','disabled');
                    var url = '<?php echo Url::to(['/home/register/mobile-code']); ?>';
                    var data = {};

                    data.number =  $('#phoneregisterform-country_code').val() + $('#phoneregisterform-phone').val();
                    data.type   = '<?php echo Yii::$app->controller->action->id; ?>';
                    $.post(url, data).done(function(r) {
                        r = eval('('+ r + ')');
                        if(r.messages.status == 1){
                        duration = 0;
                        $('#count-down').attr('disabled','');
                        var _phone =  $('#register-phone').find('.form-group.field-phoneregisterform-phone');
//                        if( _phone.hasClass('has-success') || _phone.hasClass('has-error') ||(!_phone.hasClass('has-success') &&  !_phone.hasClass('has-error')) )
//
                        $('#error_mes_p').text('<?=Yii::t("app/login","Hello there! Send text messages too often, please take a break")?>');
//                        }
                        return false;
                        }
                        if(r.messages.status == 2){
                            duration = 0;
                            $('#count-down').attr('disabled','');
                            var _phone =  $('#register-phone').find('.form-group.field-phoneregisterform-phone');
                            if( _phone.hasClass('has-success') || _phone.hasClass('has-error') ||(!_phone.hasClass('has-success') &&  !_phone.hasClass('has-error')) )
                            {
                                $('#error_mes_p').text(r.messages.message);
                            }
                            return false;
                        }
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').removeClass('has-error');
                        $('#phoneregisterform-phone').attr('aria-invalid','true');
                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').removeClass('has-error');
                        $('#phoneregisterform-country_code').attr('aria-invalid','true');
                        $('#register-phone').find('.form-group.field-phoneregisterform-country_code').find('.help-block.help-block-error').text('');
                        $('#register-phone').find('.form-group.field-phoneregisterform-phone').find('.help-block.help-block-error').text('');
                    });

                    var countDown = function() {
                        if(duration>0){
                            $('#count-down').val(duration);
                            duration--;
                        }else{
                            window.clearInterval(dt);
                            $('#count-down').attr('disabled',false).val('<?=Yii::t("app/index","Get code")?>');
                        }
                    };
                    var dt = self.setInterval(countDown,1000);
                " value='<?=Yii::t("app/index","Get code")?>'>
                <div class="help-block"></div>
            </div>

            </div>
                        <div class="col-sm-12" style="margin-top: -12px;"><p style="color: #a94442" id="error_mes_p"></p></div>
            </div>




                    <input type="button" id="phoneregisterButton" class="btn btn-primary block full-width m-b button-new-color"
                           onclick="
            var phonepatter = /^[0-9]{2,11}$/;
            var passpatter = /(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/;
            var phone = $('#phoneregisterform-phone').val().trim();
            var phonepassword = $('#phoneregisterform-password').val();
            var phonerepassword = $('#phoneregisterform-repassword').val();

            if( phone != ''
                && phonepassword.trim() != ''
                && phonerepassword.trim() != ''
                && phonepatter.test(phone)
                && passpatter.test(phonepassword.trim())
                && (phonepassword == phonerepassword)
                ){
                $('#register-phone').submit();
                $('#phoneregisterButton').attr('disabled','disabled');
            }

                " value='<?=Yii::t("app/login","Register")?>'>

                    <?php ActiveForm::end(); ?>


                </div>

            </div>

        </div>






        <p class="text-muted text-center">
            <small><?= Yii::t('app/login','Already have an account')?> &nbsp;？</small><a href="<?php echo \yii\helpers\Url::to(['/home/login/login']) ?>"><?= Yii::t('app/login','Sign in')?></a>
            | <a href="<?php echo \yii\helpers\Url::to(['/home/default/welcome']) ?>"><?= Yii::t('app/index','Home')?></a>
        </p>
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
<script>
    function myfn(e){window.event? window.event.cancelBubble = true : e.stopPropagation();}
</script>