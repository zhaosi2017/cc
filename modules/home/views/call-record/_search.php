<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="call-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
         'fieldConfig' => [
         
            'labelOptions' => [],
        ],
    ]); ?>
    <div>

    </div>
    <div class="row">
        <div class="col-lg-7">
        <?= Yii::t('app/call-record/index','Call time') ?>

             <?php    echo DateTimePicker::widget([
    'name' => 'CallRecordSearch[call_time_start]',
    'options' => ['placeholder' => ''],
    //注意，该方法更新的时候你需要指定value值
    'value' => $model->call_time_start,
    'id'=>'callrecordsearch-call_time_start',
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd HH:ii:ss',
        'todayHighlight' => true
    ]
]);?>
            <?= Yii::t('app/call-record/index','To')?>
                        <?php    echo DateTimePicker::widget([
    'name' => 'CallRecordSearch[call_time_end]',
    'id'=>'callrecordsearch-call_time_end',
    'options' => ['placeholder' => ''],
    //注意，该方法更新的时候你需要指定value值
    'value' => $model->call_time_end,
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd HH:ii:ss',
        'todayHighlight' => true
    ]
]);?>

            &nbsp;&nbsp;
            <a class="btn btn-xs btn-danger" onclick="
                $('#callrecordsearch-call_time_start').val('');
                $('#callrecordsearch-call_time_end').val('');
            "><?= Yii::t('app/call-record/index','Clear time')?></a>
            &nbsp;&nbsp;
            <?= $form->field($model,'status')->dropDownList($model->getStatusListBySearch(),['prompt'=>Yii::t('app/call-record/index','All'),'onchange'=>'
                $("#search_hide").click();
            '])->label(    Yii::t('app/call-record/index','Call status') .'：') ?>
        </div>
        <div class="col-lg-4">
            <div class="text-right no-padding">
                <?= $form->field($model, 'search_type')->dropDownList(
                [
                    1 => Yii::t('app/call-record/index','Call account'),
                    2 => Yii::t('app/call-record/index','Call nickname'),
                    3 => Yii::t('app/call-record/index','Call phone'),
                    4 => Yii::t('app/call-record/index','Called account'),
                    5 => Yii::t('app/call-record/index','Called nickname'),
                    6 => Yii::t('app/call-record/index','Called phone'),
                    7 => Yii::t('app/call-record/index','Call type'),
                ],
                ['prompt' => Yii::t('app/call-record/index','All'),'onchange'=>'clearDate()']
                )->label(false) ?>
                <?= $form->field($model, 'search_keywords')->textInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('search', ['class' => 'hide','id'=>'search_hide']) ?>
                    <button onclick = "return searchClick();" id="search" class = 'btn btn-primary m-t-n-xs button-new-color'><?= Yii::t('app/call-record/index','Search')?></button>
                    
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">


    function searchClick(){
        var start = $('#callrecordsearch-call_time_start').val(); 
        var end =  $('#callrecordsearch-call_time_end').val(); 
        if (start == "" && end != ""){
            alert('<?= Yii::t('app/call-record/index','Please also select the start time and the end time to query') ?>');
            return false;
        }
        if(start != "" && end == ""){
            alert('<?= Yii::t('app/call-record/index','Please also select the start time and the end time to query') ?>');
            return false;
        }
        return true;
    }

    function timeChange(){
    
//        var start = $('#callrecordsearch-call_time_start').val();
//        var end =  $('#callrecordsearch-call_time_end').val();
//        if(start == '' || end ==''){
//            alert('请同时选择开始时间和结束时间进行查询！');
//            return true;
//        }
        
    }

    function clearDate(){
        $('#callrecordsearch-call_time_start').val('');
        $('#callrecordsearch-call_time_end').val('');
    }
</script>