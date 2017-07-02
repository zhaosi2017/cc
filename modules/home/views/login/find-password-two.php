<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = Yii::t('app/login','Retrieve login password');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3><?= Yii::t('app/login','Retrieve login password')?></h3>

        <blockquote class="text-left" style="border: 0;">
           <?= Yii::t('app/login','We have registered your email')?>：<?php echo $model->username ?><?= Yii::t('app/login','With you Sent a message Pease fill in the verification code received')?>。
        </blockquote>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-three',
            'options'=>['class'=>'m-t text-left'],

        ]); ?>

        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'code',['labelOptions' => ['class' => 'col-sm-3 ','label'=>Yii::t('app/login','Verification code').':',
            ],])
            ->widget(Captcha::className(),[
                'captchaAction'=>'/home/login/captcha',
                'template' => '{image}<div style="display: inline-block;">{input}</div>',
            ])
            ->textInput(['autofocus' => true,'placeholder'=>Yii::t('app/login','Please input code')]);


        ?>
        <button class="btn btn-primary pull-right button-new-color"><?= Yii::t('app/login','Next')?></button>

        

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




