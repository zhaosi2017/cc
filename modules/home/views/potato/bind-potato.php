<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($isModify) {
    $this->title = '修改potato账号';
} else {
    $this->title = '绑定potato账号';
}
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-phone-number-form',
        'options'=>['class'=>'form-horizontal m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2  text-right'],
        ],
    ]); ?>

    <?php echo $form->field($model, 'bindCode',[

        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div><span>
*请输入您从potato上获取的绑定验证码</span>\n<span class=\"help-block m-b-none\" style=\"margin-top:17px;margin-left:17.5%;\">{error}</span></div>",
    ])->textInput(['placeholder' => '验证码',])->label('验证码') ?>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton($isModify ? '修改　' :'绑定', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
