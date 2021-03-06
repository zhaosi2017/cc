<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="call-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
    ]); ?>
    <div class="row">

        <div class="col-lg-6">
            <div >
                <?= $form->field($model, 'search_type')->dropDownList(
                    [
                        //1 => Yii::t('app/harassment','Blacklist account'),
                        // 2 => '黑名单电话',
                        2=>'telegram',
                        3=>'potato'
                    ],
                    ['prompt' => Yii::t('app/harassment','All')]
                )->label(false) ?>
                <?= $form->field($model, 'search_keywords')->textInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('search', ['class' => 'hide','id'=>'search_hide']) ?>
                    <?= Html::submitButton(Yii::t('app/harassment','Search'), ['class' => 'btn btn-primary m-t-n-xs button-new-color','id'=>'search']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
