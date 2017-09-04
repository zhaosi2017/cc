<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('app/harassment','Param settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title ;
?>
<div class="user-harassment">
    <div class="user-form">

        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'form-horizontal m-t'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-4\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                'labelOptions' => ['class' => 'col-sm-2 '],
            ],
        ]); ?>

        <?= $form->field($model, 'account',[

        ])->hiddenInput(['readonly' => 'readonly'])->label(false) ?>

        <?= $form->field($model, 'un_call_number',[
           'template' => "{label}<div class=\"row\">
                                    <div style=\"display:inline-block\">{input}</div>
                                    <div style=\"display:inline-block\">
                                        <span >&nbsp;&nbsp; *".Yii::t('app/harassment','Please set the number of times to be called in the fixed time')."</span>
                                    </div>
                                </div>\n
                                <label class=\"col-sm-3  text-right\" style=\"margin-top: 7px;\" for=\"user-un_call_number\"></label>
                                <div class='row'>
                                    <span class=\"help-block  \">{error}</span>
                                </div>",
        ])->textInput()->label(null,['class'=>'col-sm-3  text-right' , 'style'=>'margin-top: 7px;']) ?>

        <?= $form->field($model, 'un_call_by_same_number',[
           'template' => "{label}<div class=\"row\">
                                    <div style=\"display:inline-block\">{input}</div>
                                    <div style=\"display:inline-block\">
                                        <span >&nbsp;&nbsp;*".Yii::t('app/harassment','Please set the number of times a user has been called by the same person within a fixed time')."</span>
                                    </div>
                                    </div>\n
                                     <label class=\"col-sm-3  text-right\" style=\"margin-top: 7px;\" for=\"user-un_call_number\"></label>
                                    <div class='row'>
                                       
                                        <span class=\"help-block m-b-none\">{error}</span>
                                    </div>",
        ])->textInput()->label(null,['class'=>'col-sm-3  text-right' , 'style'=>'margin-top: 7px;'])  ?>

        <?= $form->field($model, 'long_time',[

                'template' => "{label}<div class=\"row\">
                                        <div style=\"display:inline-block\">{input}</div>
                                        <div style=\"display:inline-block\">
                                            <span >&nbsp;&nbsp;*".Yii::t('app/harassment','Please set the fixed time, unit: minutes, this time will affect the number of calls and the same number of calls')."</span>
                                        </div>
                                        </div>\n
                                         <label class=\"col-sm-3  text-right\" style=\"margin-top: 7px;\" for=\"user-un_call_number\"></label>
                                        <div class='row'>
                                           <span class=\"help-block m-b-none\" >{error}</span>
                                        </div>",
        ])->textInput()
         ->label(null,['class'=>'col-sm-3  text-right' , 'style'=>'margin-top: 7px;']) ?>

        <div class="form-group">
           <div class="col-sm-3"></div><div><button type="submit" class="btn btn-primary button-new-color" style="width: 174px;"><?= Yii::t('app/harassment','Finshed')?></button>    </div>
                     
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<?php 
 echo '<style type="text/css">
    .help-block {
        padding-left: 184px;
    }
</style>';
?>


