<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = !empty($model->username)?Yii::t('app/user/bind-username', 'Edit UserName'):Yii::t('app/user/bind-username', 'Build UserName');
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
            'labelOptions' => ['class' => 'col-sm-1 text-right'],
        ],
    ]); ?>

    <?= $form->field($model, 'username',[
        'template' => "{label}\n<div class=\"col-sm-3\" > {input} 
            </div> 
                <span class=\"col-sm-3 \" style=\" padding-top:9px;\">*".
                    Yii::t('app/user/bind-username' , 'Please Entry your UserName')
                ."</span> 
                \n<br><div style=\"height:20px; \"></div><label class = \"col-sm-1 \"></label><div style=\" width: 67%;padding-left: 31px !important; \">
                
                 <span style=\" padding-top:8px;\">{error}</span>
                 
            </div>",
    ])->textInput()->label(Yii::t('app/user/bind-username' ,'UserName'),['style'=>'font-size: 17px;padding-top: 5px;padding-right:2px;']) ?>

    <br>
    <div class="form-group">
        <div class="col-sm-1"></div>
        <div class="col-sm-3" >
            <button style="width: 100%;" class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success button-new-color") : ( "btn btn-primary button-new-color");echo $btnnn; ?> ' style="    width: 23%;margin-left: 128px;" >
                            <?= Yii::t('app/user/bind-username' ,'Save')?>
                        </button>
        </div>
        <div class="col-sm-3"></div>
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