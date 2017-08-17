<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;


?>



<div>
    <div>
        <div>电话号码：<?= $model->number ?></div>
        <div>价格：<?= $model->price ?></div>
        <div>结束：<?= $model->comment ?></div>
        <div>状态：<?= $model->status ?></div>
        <div>启用时间：<span style="display: none;" id="begin_time_1"><?= strtotime(date('Y-m-d',$model->begin_time)) ?></span><?= date('Y-m-d',$model->begin_time) ?></div>
        <div>结束时间：<span style="display: none;" id="end_time_1"><?= strtotime(date('Y-m-d',$model->end_time)) ?></span><?= date('Y-m-d',$model->end_time) ?></div>
    </div>
    <form action="/home/number/sure-buy" method="post">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" name="callnumberid" value="<?= $model->id?>">
        <input type="hidden" name="number" value="<?= $model->number?>">
        <?= Yii::t('app/call-record/index','Call time') ?>

        <?php    echo DateTimePicker::widget([
            'name' => 'begin_time',
            'options' => ['placeholder' => ''],
            //注意，该方法更新的时候你需要指定value值
            'value' =>date('Y-m-d'),
            'id'=>'callrecordsearch-begin_time',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd ',
                'todayHighlight' => true,
                'minView'=> "2",



            ],
            'pluginEvents' => [
                "changeDate" => "function(e) {dd = e.date; var tt = parseInt(dd.getTime()/1000);var bt = parseInt($('#begin_time_1').html());var et = parseInt($('#end_time_1').html());
                 var endTime = $('#callrecordsearch-end_time').val();
                 if( tt < bt || tt > et || (endTime && tt > endTime)){alert('请输入正确时间');$('#callrecordsearch-begin_time').val('');return false;} }",
                ],


        ]);?>
        <?= Yii::t('app/call-record/index','To')?>
        <?php    echo DateTimePicker::widget([
            'name' => 'end_end',
            'id'=>'callrecordsearch-end_time',
            'options' => ['placeholder' => ''],

            //注意，该方法更新的时候你需要指定value值
            'value' =>  date('Y-m-d',strtotime("+1 day")),
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
//                'formatDate'=>'Y/m/d'
                'minView'=> "2",
            ],
            'pluginEvents' => [
                "changeDate" => "function(e) {ddd = e.date; var ttt = parseInt(ddd.getTime()/1000);var btt = parseInt($('#begin_time_1').html());var ett = parseInt($('#end_time_1').html());
                 var beginTime = $('#callrecordsearch-end_time').val();
                 if( ttt < btt || ttt > ett || (beginTime && ttt < beginTime)){ $('#callrecordsearch-end_time').val('');alert('请输入正确时间');return false;} }",
            ],

        ]);?>

        <input type="submit" value="提交">
    </form>
</div>