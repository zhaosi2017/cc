<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

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
                <li role="presentation" class="active"><a href="#" >邮箱</a></li>
                <li role="presentation"><a href="/home/register/phone-index" >电话</a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">


                    <?php $form = ActiveForm::begin([
                        'action' => ['index#home'],
                        'id' => 'register-email',
                        'options'=>['class'=>'m-t text-left'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                        ],
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder'=>'账号(仅支持邮箱：如xxx@gmail.com)',
                    ])->label(false) ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码(至少8个字符,由大小写字母数字组合)'])->label(false) ?>

                    <?= $form->field($model, 'rePassword')->passwordInput(['placeholder'=>'重复密码'])->label(false) ?>

                    <input type="button" id="emailregisterButton" class="btn btn-primary block full-width m-b" onclick="
            var userpatter = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            var passpatter = /(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/;
            var username = $('#registerform-username').val().trim();
            var password = $('#registerform-password').val();
            var rePassword = $('#registerform-repassword').val();
            console.log(username);
            console.log(password);
            console.log(rePassword);
            if( username != ''
                && password.trim() != ''
                && rePassword.trim() != ''
                && userpatter.test(username)
                && passpatter.test(password.trim())
                && (password == rePassword)
                ){
                console.log('success');
                $('#register-email').submit();
                $('#registerButton').attr('disabled','disabled');
            }

                " value="注 册">

                    <?php ActiveForm::end(); ?>




                </div>





              

            </div>

        </div>






        <p class="text-muted text-center">
            <small>已经有账户了？</small><a href="<?php echo \yii\helpers\Url::to(['/home/login/index']) ?>">点此登录</a>
        </p>
    </div>
</div>


