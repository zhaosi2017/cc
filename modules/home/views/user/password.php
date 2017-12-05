<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app/user/password' , 'Edit The Password');
$this->params['breadcrumbs'][] = ['label'=>Yii::t('app/user/password', 'User'),'url'=>''];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal m-t'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-3\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-1 text-right'],
        ],
    ]) ?>

    <?= $form->field($model, 'password',[
         'template' => "{label}\n<div class=\"col-sm-3\">{input}</div> 
                                <div class=\"col-sm-8\">
                                <span  style='line-height: 34px;'>*".
                                    Yii::t('app/user/password' , 'Please Enter the old Password')
            
                                ."</span></div>\n
                                <div>
                                <div class=\"col-sm-12\"></div>
                                <label class=\"col-sm-1\"></label>
                                    <span class=\" col-sm-3 help-block text-left \" \">{error}</span>
                                    <div class=\"col-sm-8\"></div>
                                </div>",
    ])->passwordInput()->label(Yii::t('app/user/password' ,'Old Password') ,['style'=>'padding-top:7px']) ?>

    <?= $form->field($model, 'newPassword',[
             'template' => "{label}\n<div class=\"col-sm-3\">{input}</div> 
                                    <div class=\"col-sm-8\">
                                    <span  style='line-height: 34px;'>*".
                                        Yii::t('app/user/password' , 'The password contains at least 8 characters, including at least two characters:Uppercase letters, lowercase letters, numbers.')
                                    ."</span>\n</div>
                                    <div class=\"col-sm-12\"></div>
                                    <label class=\"col-sm-1\"></label>
                                    
                                       <span class=\"col-sm-3 help-block text-left \" \">{error}</span>
                                       <div class=\"col-sm-8\"></div>
                                    ",
    ])->passwordInput()
    ->label(Yii::t('app/user/password' , 'Password'),['style'=>'padding-top:7px'])
    ?>

    <?= $form->field($model, 'rePassword',[
             'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>
                                    <div class=\"col-sm-8\">
                                    <span  style='line-height: 34px;'> *".
                                        Yii::t('app/user/password' ,'The password contains at least 8 characters, including at least two characters:Uppercase letters, lowercase letters, numbers.')
                                    ."</span>\n</div>
                                    <div class=\"col-sm-12\"></div>
                                    <label class=\"col-sm-1\"></label>
                                    
                                        <span class=\"col-sm-3 help-block text-left \"  \">{error}</span>
                                        <div class=\"col-sm-8\"></div>
                                    ",
    ])->passwordInput()
    ->label(Yii::t('app/user/password' , 'Repeat'),['style'=>'padding-top:7px'])
    ?>

    <div class="form-group">
        <label class="col-sm-1" for="">

        </label>
        <div class="col-sm-3" >
            <?= Html::submitButton(Yii::t('app/user/password','Submit'), ['class' => ' btn btn-primary button-new-color' , 'style'=>'width:100%;','id'=>"passwordupdate"]) ?>
        </div>
        <span class="col-sm-5"></span>
    </div>

    <?php ActiveForm::end(); ?>

</div>

