<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '修改用户名';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-username-form',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-5\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-1 '],
        ],
    ]); ?>

    <?= $form->field($model, 'account',[
        'template' => "{label}\n<div class=\"col-sm-3\"> {input}  </div> <span class=\"col-sm-2 \">*请输入邮箱</span> \n<br><div style=\"height:20px;\"></div><label class = \"col-sm-1 \"></label><div style=\" width: 67%;\"><span style=\"\">{error}</span></div>",
    ])->textInput() ?>

    <br>
    <div class="form-group">
       
        <div style=" " > <button class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success button-new-color") : ( "btn btn-primary button-new-color");echo $btnnn; ?> ' style="    width: 23%;margin-left: 108px;" >下一步</button></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
echo '<style type="text/css">
    .help-block{
        padding-left: 108px;
    }'
?>
</style>