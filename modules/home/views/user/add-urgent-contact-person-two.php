<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($isModify) {
    $this->title = '修改紧急联系人二';
} else {
    $this->title = '添加紧急联系人二';
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


    <?php echo $form->field($model, 'urgent_contact_person_two')->textInput(['placeholder' => '紧急联系人昵称',])->label('紧急联系人') ?>

    <div class="row form-inline">

        <div class="col-sm-2 text-right">
            <div class="form-group" style="">
                <label for="task-customer-category" class="col-sm-12 ">紧急联系人电话</label>
            </div>
        </div>

        <div class="col-sm-10">
         
            <?php echo $form->field($model, 'urgent_contact_two_country_code', [
               
                'template' => "{label}\n<div style=\"width:130px;\">&nbsp;+{input}\n<span style=\"height:18px;min-height:18px;\" class=\"help-block m-b-none\">{error}</span></div>",
            ])->textInput(['placeholder'=>'国码','size'=>'5'])->label(false) ?>

            <?php echo $form->field($model, 'urgent_contact_number_two',[
               
                'template' => "{label}\n<div>&nbsp;{input}\n<span style=\"height:18px;\" class=\"help-block m-b-none\">{error}</span></div>",
            ])->textInput(['placeholder' => '紧急联系人号码', 'size'=>'17'])->label(false) ?>
        </div>

    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton($isModify ? '修改　' :'添加', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>

<?php echo '<style type="text/css">
    .has-error{
        margin-bottom: 1px;
    }
</style>';
?>