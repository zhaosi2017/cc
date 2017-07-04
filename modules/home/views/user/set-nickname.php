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
            'template' => "{label}\n<div class=\"col-sm-3\"> {input}  </div> 
                                    <span class=\"col-sm-2 control-label\" style='margin-top: 7px'> 
                                        * ".Yii::t('app/user/set-nickname' ,'Nickname is 2 to 6 Chinese characters')//."昵称为2到6个汉字
                                   ."</span> \n<br>
                                    <div style=\"height:20px;\"></div>
                                    <label class = \"col-sm-1 \"></label>
                                    <div style=\" width: 67%;\">
                                        <span style=\"\">{error}</span>
                                    </div>",
    ])->textInput()
    ->label(Yii::t('app/user/set-nickname' ,'Nickname')/*'昵称'*/ ,['class'=>'col-sm-1' , 'style'=>'margin-top: 7px;'])
    ?>

    <br>
    <div class="form-group">
        <!-- <?= Html::submitButton(Yii::t('app/user/set-nickname' ,'Save')/*'保存'*/, ['class' => $model->isNewRecord ? 'btn btn-success button-new-color' : 'btn btn-primary button-new-color'],[
            'template' => "<div style=\" width: 67%;margin: auto;\" >{button}</div>",
        ]) ?> -->
       <div style=" " > <button class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success button-new-color") : ( "btn btn-primary button-new-color");echo $btnnn; ?> ' style="    width: 23%;margin-left: 128px;" ><?= Yii::t('app/user/set-nickname' ,'Save')?></button></div>
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