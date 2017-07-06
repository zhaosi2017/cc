<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = empty($model->account)?Yii::t('app/user/bind-email','Bind Eamil'):Yii::t('app/user/bind-email','Edit Eamil');
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

    <?= $form->field($model, 'account',[
        'template' => "{label}\n<div class=\"col-sm-3\"> {input}  </div> 
                                <span class=\"col-sm-3 \" style='padding-top: 9px'>*".
                                        Yii::t('app/user/bind-email' , 'Please Entry your Eamil address')
                                ."</span> \n<br>
                                <div style=\"height:20px;\"></div>
                                <label class = \"col-sm-1  text-right\"></label>
                                <div  style='margin-left: 20px;'  > 
                                    <span>{error}</span>
                                </div>",
    ])->textInput()
    ->label(Yii::t('app/user/bind-email','Email'),['style'=>'padding-top:5px ;font-size:17px' ,'class'=>'col-sm-1  text-right']) ?>

    <div class="col-sm-12"></div>
    <div class="form-group">

        <div class="col-sm-1"></div>
        <div class="col-sm-3" >
            <button style="width: 100%;" class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success button-new-color") : ( "btn btn-primary button-new-color");echo $btnnn; ?> ' style="    width: 23%;margin-left: 128px;" >
                <?= Yii::t('app/user/bind-email' , 'Next')?>

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