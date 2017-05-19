<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = '添加紧急联系人二';

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
            'labelOptions' => ['class' => 'col-sm-2 control-label text-right'],
        ],
    ]); ?>


    <?php echo $form->field($model, 'urgent_contact_person_two')->textInput(['placeholder' => '紧急联系人昵称',])->label('紧急联系人') ?>

    <div class="row form-inline">

        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label">紧急联系人电话</label>
            </div>
        </div>

        <div class="col-sm-10">
            <div class="form-group">
                <div class="col-sm-1">
                    +
                    <div class="help-block"></div>
                </div>
            </div>
            <?php echo $form->field($model, 'urgent_contact_two_country_code', [
                'template' => "{label}\n<div class='col-sm-4'>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            ])->textInput(['size' => 5,'placeholder'=>'国码',])->label(false) ?>

            <?php echo $form->field($model, 'urgent_contact_number_two')->textInput(['placeholder' => '紧急联系人号码', 'size'=>'21'])->label(false) ?>
        </div>

    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton('绑定', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
