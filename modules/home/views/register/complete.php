<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '注册成功';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>注册成功</h3>

        <blockquote class="text-left" style="border: 0;">
            <p>请您牢记您的邮箱账号和密码！！！</p>
            <p>您的邮箱账号：<?php echo $model->username ?></p>
            <p>您的密码：<?php echo $model->password ?></p>
        </blockquote>
        <?php $form = ActiveForm::begin([
            'id' => 'verify-form',
            'action' => 'complete',
            'options'=>['class'=>'m-t text-left'],
        ]); ?>

        <?= Html::submitButton('确定', ['class' => 'btn btn-primary block full-width m-b button-new-color']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>



