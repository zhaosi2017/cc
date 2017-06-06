<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '修改昵称';

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-nickname-form',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-5\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'nickname')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
