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
    <div>
        <p style="margin-left: 10%;font-size: 13px;font-weight: 700;">操作步骤：</p>
    </div>
    <div class="form-group" style="margin-left: 16.6%;">

        <p>1、请先在potato上登录个人账号</p>
        <p>2、添加机器人好友：<?php echo Yii::$app->params['potato_name'];?></p>
        <p>3、分享自己名片给机器人</p>
        <p>4、 选择绑定操作</p>
        <p>5、将获取的验证码填写在下方输入框中进行绑定操作</p>
    </div>

    <?php echo $form->field($model, 'bindCode',[

        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div><span>
*请输入您从potato上获取的绑定验证码</span>\n<span class=\"help-block m-b-none\" style=\"margin-top:17px;margin-left:17.5%;\">{error}</span></div>",
    ])->textInput(['placeholder' => '验证码',])->label('验证码:') ?>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton($isModify ? '修改　' :'绑定', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
