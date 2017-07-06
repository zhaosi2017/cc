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
        <div class="form-group">
            <div class="col-sm-4">

            </div>
            <div class="col-sm-8">
                <h3><?= Yii::t('app/login','Retrieve login password')?></h3>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4"></div>
            <div class="col-sm-8" style="padding-left: 0px;">
                <div class="text-left">
                <?= Yii::t('app/login','We have sent verification code to your registered mail:')?>：<?php echo $model->username ?>
                </div>
                <div class="text-left">
                    <?= Yii::t('app/login','Please  fill in the received code.')?>
                </div>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'find-password-three',
            'options'=>['class'=>'m-t text-left'],
            'fieldConfig' => [

                'labelOptions' => ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px;'],
            ],

        ]); ?>

        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'code',['labelOptions' => ['class' => 'col-sm-4 ','label'=>Yii::t('app/login','Verification code').':',
            ],])
            ->widget(Captcha::className(),[
                'captchaAction'=>'/home/login/captcha',
                'template' => '{image}<div  style="display: inline-block;">{input}</div>',
            ])
            ->textInput(['autofocus' => true,'placeholder'=>Yii::t('app/login','Please input code')]);


        ?>
        <div class="form-group">
            <label class="col-sm-4"></label>
            <div class="col-sm-8" style="padding: 0;">
                <button class="btn btn-primary pull-right button-new-color" style="width: 100%;"><?= Yii::t('app/login','Next')?></button>
            </div>
        </div>
        

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
       margin-left: 20px !important;
  }

</style>';


?>




