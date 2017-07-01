<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = !empty($model->username)?'修改用户名':'绑定用户名';
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

    <?= $form->field($model, 'username',[
        'template' => "{label}\n<div class=\"col-sm-3\"> {input}  </div> <span class=\"col-sm-2 \" style=\" padding-top:9px;\">*请输入用户名</span> \n<br><div style=\"height:20px; \"></div><label class = \"col-sm-1 \"></label><div style=\" width: 67%; \"><span style=\" padding-top:8px;\">{error}</span></div>",
    ])->textInput()->label('用户名',['style'=>'font-size: 17px;padding-top: 5px;']) ?>

    <br>
    <div class="form-group">
        <!-- <?= Html::submitButton('保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'],[
            'template' => "<div style=\" width: 67%;margin: auto;\" >{button}</div>",
        ]) ?> -->
        <div style=" " > <button class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success button-new-color") : ( "btn btn-primary button-new-color");echo $btnnn; ?> ' style="    width: 23%;margin-left: 128px;" >保存</button></div>
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