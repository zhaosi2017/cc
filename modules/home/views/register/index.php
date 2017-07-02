<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\RegisterForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

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
                <li role="presentation" class="active"><a href="#" ><?= Yii::t('app/login','Email')?></a></li>
                <li role="presentation"><a href="/home/register/phone-index" ><?= Yii::t('app/login','Phone')?></a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">


                    <?php $form = ActiveForm::begin([
                        'action' => ['register#home'],
                        'id' => 'register-email',
                        'options'=>['class'=>'m-t text-left'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                        ],
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder'=> Yii::t('app/login','Account only  email: eg xx@gmail.com'),
                    ])->label(false) ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>Yii::t('app/login','Password  least 8  upper & lower char')])->label(false) ?>

                    <?= $form->field($model, 'rePassword')->passwordInput(['placeholder'=>Yii::t('app/login','Repeat password')])->label(false) ?>

                    <input type="button" id="emailregisterButton" class="btn btn-primary block full-width m-b button-new-color" onclick="
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

                " value=<?= Yii::t('app/login','Register')?>>

                    <?php ActiveForm::end(); ?>




                </div>





              

            </div>

        </div>






        <p class="text-muted text-center">
            <small><?= Yii::t('app/login','Already have an account')?> &nbsp;？</small><a href="<?php echo \yii\helpers\Url::to(['/home/login/login']) ?>"><?= Yii::t('app/login','Login')?></a>
        </p>
    </div>
</div>


