<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = '输入验证码';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>找回登录密码</h3>

        <blockquote class="text-left">
            我们已经向您的注册邮箱：<?php echo $model->username ?>发送了一封邮件,请填写收到的验证码。
        </blockquote>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-three',
            'options'=>['class'=>'m-t text-left'],

        ]); ?>

        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'code',['labelOptions' => ['class' => 'col-sm-3 ','label'=>'验证码:',
            ],])
            ->widget(Captcha::className(),[
                'captchaAction'=>'/home/login/captcha',
                'template' => '{image}<div style="display: inline-block;">{input}</div>',
            ])
            ->textInput(['autofocus' => true,'placeholder'=>'请输入验证码']);


        ?>
        <button class="btn btn-primary pull-right">下步</button>

        

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php

echo '<style type="text/css"> 
  #loginform-code {
    width: 200px;
    display: inline-block;
  
  }

  .col-sm-3 .control-label{
    color:black;
  }

  .help-block-error{
    padding-left:78px !important;
  }

</style>';


?>




