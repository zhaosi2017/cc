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
                                                    'name' => 'FinalChangeSearch[call_time_start]',
                                                    'options' => ['placeholder' => ''],
                                                    //注意，该方法更新的时候你需要指定value值
                                                    'value' => $model->call_time_start,
                                                    'id'=>'finalchangesearch-call_time_start',
                                                    'pluginOptions' => [
                                                        'autoclose' => true,
                                                        'format' => 'yyyy-mm-dd HH:ii:ss',
                                                        'todayHighlight' => true
                                                    ]
                                                ]);
             ?>
            <?= Yii::t('app/call-record/index','To')?>
                        <?php    echo DateTimePicker::widget([
                                                            'name' => 'FinalChangeSearch[call_time_end]',
                                                            'id'=>'finalchangesearch-call_time_end',
                                                            'options' => ['placeholder' => ''],
                                                            //注意，该方法更新的时候你需要指定value值
                                                            'value' => $model->call_time_end,
                                                            'pluginOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd HH:ii:ss',
                                                                'todayHighlight' => true
                                                            ]
                                                        ]);
                        ?>

            &nbsp;&nbsp;
            <a class=" btn-danger button-new-1  " style="" onclick="
                $('#finalchangesearch-call_time_start').val('');
                $('#finalchangesearch-call_time_end').val('');
            "><?= Yii::t('app/call-record/index','Clear time')?></a>
            &nbsp;&nbsp;
            <?= $form->field($model,'status')->dropDownList($model->getStatusListBySearch(),['prompt'=>Yii::t('app/call-record/index','All'),'onchange'=>'
                $("#search_hide").click();
            '])->label(    Yii::t('app/call-record/index','Call status') .'：') ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">


    function searchClick(){
        var start = $('#finalchangesearch-call_time_start').val();
        var end =  $('#finalchangesearch-call_time_end').val();
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


    }

    function clearDate(){
        $('#finalchangesearch-call_time_start').val('');
        $('#finalchangesearch-call_time_end').val('');
    }
</script>