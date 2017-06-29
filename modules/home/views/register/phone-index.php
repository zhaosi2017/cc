<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title = '注册';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">&nbsp;</h1>
        </div>
        <h3>注册个人账号</h3>





        <div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a href="/home/register/register" >邮箱</a></li>
                <li role="presentation" class="active"><a href="#" >电话</a></li>

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
                        'placeholder'=>'国码',
                        'size'=>5,
                    ])->label(false) ?>
                    </div>
                     <div  class="col-sm-6" style="display: inline-block;margin-left: 10px;">
                    <?= $forms->field($model, 'phone')->textInput([
                        'autofocus' => true,
                        'placeholder'=>'电话号码',
                    ])->label(false) ?>
                    </div>

                    <?= $forms->field($model, 'password')->passwordInput(['placeholder'=>'密码(至少8个字符,由大小写字母数字组合)'])->label(false) ?>

                    <?= $forms->field($model, 'rePassword')->passwordInput(['placeholder'=>'重复密码'])->label(false) ?>



                    <div class="row">
                          <div class="col-sm-6 " style="margin-left: -12px;">
                  <?php echo $forms->field($model, 'code', [
                'template' => "{label}\n<div class='m-l-sm'>{input}\n<span style=\"height:28px;\" class=\"help-block m-b-none\">{error}</span></div>",
            ])->widget(Captcha::className(),[
                'captchaAction'=>'/home/user/captcha',
                'template' => '<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-10">{input}</div></div>',
            ])
                ->textInput(['size' => 18,'placeholder'=>'请输入验证码'])
                ->label(false)
            ?>
            </div>
            <div class="col-sm-6 " style="margin-left: -12px;">
                <div class="form-group" style="    ">
                <input type="button" id="count-down" class="form-control"  style="" onclick="
                    if($('#phoneregisterform-country_code').val() == ''){
                        alert('国码不能为空');
                        return false;
                    }
                    if($('#phoneregisterform-phone').val() == ''){
                        alert('电话不能为空');
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
                            alert('你好！发送短信太频繁,请稍微休息哈');
                        }
                    });

                    var countDown = function() {
                        if(duration>0){
                            $('#count-down').val(duration);
                            duration--;
                        }else{
                            window.clearInterval(dt);
                            $('#count-down').attr('disabled',false).val('获取验证码');
                        }
                    };
                    var dt = self.setInterval(countDown,1000);
                " value="获取验证码">
                <div class="help-block"></div>
            </div>
            </div>
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

                " value="注 册">

                    <?php ActiveForm::end(); ?>


                </div>

            </div>

        </div>






        <p class="text-muted text-center">
            <small>已经有账户了？</small><a href="<?php echo \yii\helpers\Url::to(['/home/login/login']) ?>">点此登录</a>
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