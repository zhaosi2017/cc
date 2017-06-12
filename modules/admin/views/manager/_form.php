<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Manager */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manager-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal m-t'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'account')->textInput(['placeholder'=>'请输入账号']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'请输入账号']) ?>

    <?= $form->field($model, 'nickname')->textInput(['placeholder'=>'请输入管理员昵称']) ?>

    <?= $form->field($model, 'role_id')->dropDownList($model['roles'],['prompt'=>'请选择']) ?>

    <?php if(!$model->isNewRecord){ ?>
    <?= $form->field($model, 'status')->radioList([0 => '正常', 2 => '冻结']) ?>

    <?= $form->field($model, 'remark')->widget(Redactor::className(),[
        'clientOptions' => [
            'lang' => 'zh_cn',
            'imageUpload' => false,
            'fileUpload' => false,
            'plugins' => [
                'clips',
                'fontcolor'
            ],
            'placeholder'=> '请填写原因',
            'maxlength'=>500,
         
        ],
        'options'=>[
            'value'=>'',
        ],
    ])->label('原因') ?>

    <?= $form->field($model, 'login_ip')->textInput(['value'=>Yii::$app->request->userIP,'readonly'=>true]) ?>

    <?php } ?>

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? '创建' : '保存', ['class'=>'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
