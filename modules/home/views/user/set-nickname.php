<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '修改昵称';
$this->params['breadcrumbs'][] = $this->title;
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
            'labelOptions' => ['class' => 'col-sm-1 '],
        ],
    ]); ?>

    <?= $form->field($model, 'nickname',[
            'template' => "{label}\n<div class=\"col-sm-3\"> {input}  </div> <span class=\"col-sm-2 control-label\">*昵称为2到6个汉字</span> \n<br><div style=\"height:20px;\"></div><div style=\" width: 67%;margin: auto;\">{error}</div>",
    ])->textInput() ?>

    <br>
    <div class="form-group">
        <!-- <?= Html::submitButton('保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'],[
            'template' => "<div style=\" width: 67%;margin: auto;\" >{button}</div>",
        ]) ?> -->
       <div style=" margin: auto;" > <button class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success") : ( "btn btn-primary");echo $btnnn; ?> ' style="    width: 257px;margin-left: 108px;" >保存</button></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
