<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = '修改绑定potato';

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-phone-number-form',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-10\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label text-right'],
        ],
    ]); ?>


    <?php echo $form->field($model, 'urgent_contact_number_one')->textInput(['size' => 15, 'placeholder' => '紧急联系人一的号码', 'value'=>''])->label('联系人一') ?>

    <div class="row form-inline">

        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label">紧急联系人电话:</label>
            </div>
        </div>

        <div class="col-sm-10">
            <?php echo $form->field($model, 'potato_country_code', [
                'template' => "{label}\n<div>&nbsp;+{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            ])->textInput(['size' => 5,'placeholder'=>'国码','value'=>''])->label(false) ?>

            <?php echo $form->field($model, 'potato_number')->textInput(['placeholder' => '您的potato号码'])->label(false) ?>
        </div>


    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton('绑定', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
