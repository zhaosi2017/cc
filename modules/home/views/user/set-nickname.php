<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = empty($model->nickname) ? Yii::t('app/user/set-nickname','Set nickname'): Yii::t('app/user/set-nickname','Update nickname');
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
//            'template' => "{label}\n<div class=\"col-sm-5\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class'=>'col-sm-1 text-right'],
        ],
    ]); ?>

    <?= $form->field($model, 'nickname',[
            'template' => "<span class=\"text-right\">{label}</span>\n<div class=\"col-sm-3\"> {input}  </div> 
                                    <span class=\"col-sm-5 text-left\" style='margin-top: 7px'> 
                                        * ".Yii::t('app/user/set-nickname' ,'Nickname is 6 to 40 characters')//."昵称为2到6个汉字
                                   ."</span> \n<br>
                                    <div class=\"col-sm-12\"></div>
                                    
                                    <label class = \"col-sm-1 \"></label>
                                    <div class=\"col-sm-3\">
                                        <span class=\"text-left\" style=\"\">{error}</span>
                                    </div>
                                    <div class=\"col-sm-5\"></div>
                                    </div>
                                    ",
    ])->textInput()
    ->label(Yii::t('app/user/set-nickname' ,'Nickname')/*'昵称'*/ ,['class'=>'col-sm-1' , 'style'=>'margin-top: 7px;'])
    ?>


    <div class="form-group">
        <div class="col-sm-12"></div>
        <div class="col-sm-1"></div>
       <div class="col-sm-3" >
           <button class='<?php $btnnn = $model->isNewRecord ? ( "btn btn-success button-new-color") : ( "btn btn-primary button-new-color");echo $btnnn; ?> ' style="    width: 100%;" ><?= Yii::t('app/user/set-nickname' ,'Save')?></button></div>
    </div>
    <div class="col-sm-5"></div>

    <?php ActiveForm::end(); ?>

</div>
