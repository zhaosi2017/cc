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
            <?= $form->field($model,'call_time_start')->input('date',['prompt'=>'开始时间'])->label('呼叫时间：') ?>
            至
            <?= $form->field($model,'call_time_end')->input('date',['prompt'=>'结束时间'])->label(false) ?>
              <a class="btn btn-xs btn-danger" onclick="
                $('#callrecordsearch-call_time_start').val('');
                $('#callrecordsearch-call_time_end').val('');
            ">清除时间</a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= $form->field($model,'status')->dropDownList($model->getStatusList(),['prompt'=>'全部','onchange'=>'
                $("#search_hide").click();
            '])->label('呼叫状态：') ?>
        </div>
        <div class="col-lg-6">
            <div class="text-right no-padding">
                <?= $form->field($model, 'search_type')->dropDownList(
                [
                    1 => '主叫账号',
                    2 => '主叫昵称　',
                    3 => '主叫电话',
                    4 => '被叫账号',
                    5 => '被叫昵称',
                    6 => '被叫电话',
                    7 => '呼叫类型',
                ],
                ['prompt' => '全部']
                )->label(false) ?>
                <?= $form->field($model, 'search_keywords')->textInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('search', ['class' => 'hide','id'=>'search_hide']) ?>
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary m-t-n-xs','id'=>'search']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
