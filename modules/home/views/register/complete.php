<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app/login','Registration success');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h2><?= Yii::t('app/login','Registration success')?></h2>

        <blockquote class="text-left" style="border: 0;">
            <p><?= Yii::t('app/login','Please keep in mind your  account and password')?>！！！</p>
           <?php  if($model instanceof  \app\modules\home\models\RegisterForm) {?>
                <p><?= Yii::t('app/login','Your email account')?>：<?php echo $model->username ?></p>
                <p><?= Yii::t('app/login','Your password')?>：<?php echo $model->password ?></p>
            <?php }else{ ?>
                <p><?= Yii::t('app/login','Your phone account')?>：<?php echo $model->phone ?></p>
                <p><?= Yii::t('app/login','Your password')?>：<?php echo $model->password ?></p>
            <?php } ?>

        </blockquote>
        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'complete',
            'options'=>['class'=>'m-t text-left'],
        ]); ?>

        <?= Html::submitButton(Yii::t('app/login','Ok'), ['class' => 'btn btn-primary block full-width m-b button-new-color']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



