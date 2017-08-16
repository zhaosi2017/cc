<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="call-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['platform'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
    ]); ?>
    <div class="row">
        <div class="col-lg-10">
                <?= $form->field($model,'start_time')->input('date',['prompt'=>'开始时间'])->label('租赁时间：') ?>
                至
                <?= $form->field($model,'close_time')->input('date',['prompt'=>'结束时间'])->label(false) ?>
                <a class="btn btn-xs btn-danger" onclick="
                    $('#start_time').val('');
                    $('#close_time').val('');
                ">清除时间</a>
            <?= $form->field($model, 'search_keywords')->textInput()->label('电话号码') ?>

        </div>
        <div class="col-lg-2">
            <div class="text-right no-padding">
                <div class="form-group">
                    <?= Html::submitButton('search', ['class' => 'hide','id'=>'search_hide']) ?>
                    <button onclick = "return searchClick();" id="search" class = 'btn btn-primary m-t-n-xs'>搜索</button>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">

    function searchClick(){
        var start = $('#start_time').val();
        var end =  $('#close_time').val();
        if (start == "" && end != ""){
            alert('请同时选择开始时间和结束时间进行查询！');
            return false;
        }
        if(start != "" && end == ""){
            alert('请同时选择开始时间和结束时间进行查询！');
            return false;
        }
        return true;
    }

    function timeChange(){

        var start = $('#start_time').val();
        var end =  $('#close_time').val();
        if(start == '' || end ==''){
            alert('请同时选择开始时间和结束时间进行查询！');
            return true;
        }

    }

    function clearDate(){
        $('#start_time').val('');
        $('#close_time').val('');
    }
</script>
