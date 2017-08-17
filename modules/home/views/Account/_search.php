<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecordSearch */
/* @var $form yii\widgets\ActiveForm */
use \app\modules\home\models\FinalChangeSearch;
?>

<div class="call-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['recharge'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
         'fieldConfig' => [

            'labelOptions' => [],
        ],
    ]); ?>
    <div>

    </div>
    <div class="row">
        <div class="col-lg-8">
        <?= Yii::t('app/call-record/index','Call time') ?>

             <?php    echo DateTimePicker::widget([
                                                    'name' => 'FinalChangeSearch[start_time]',
                                                    'options' => ['placeholder' => ''],
                                                    //注意，该方法更新的时候你需要指定value值
                                                    'value' => $param['start_time'],
                                                    'id'=>'finalchangesearch-start_time',
                                                    'pluginOptions' => [
                                                        'autoclose' => true,
                                                        'format' => 'yyyy-mm-dd HH:ii:ss',
                                                        'todayHighlight' => true
                                                    ]
                                                ]);
             ?>
            <?= Yii::t('app/call-record/index','To')?>
                        <?php    echo DateTimePicker::widget([
                                                            'name' => 'FinalChangeSearch[end_time]',
                                                            'id'=>'finalchangesearch-end_time',
                                                            'options' => ['placeholder' => ''],
                                                            //注意，该方法更新的时候你需要指定value值
                                                            'value' => $param['end_time'],
                                                            'pluginOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd HH:ii:ss',
                                                                'todayHighlight' => true
                                                            ]
                                                        ]);
                        ?>

            &nbsp;&nbsp;
            <a class=" btn-danger button-new-1  " style="" onclick="
                $('#finalchangesearch-start_time').val('');
                $('#finalchangesearch-end_time').val('');
            "><?= Yii::t('app/call-record/index','Clear time')?></a>
            &nbsp;&nbsp;
<!--            -->
            <select class="form-control" name="FinalChangeSearch[change_type]" id="finalchangesearch-change_type">
                <option value="0" <?php if($param['change_type']==0){echo 'selected';}?>>全部</option>
                <option <?php if($param['change_type']==FinalChangeSearch::FINAL_CHANGE_TYPE_RECHARGE){echo 'selected';}?> value="<?= FinalChangeSearch::FINAL_CHANGE_TYPE_RECHARGE ?>"><?php echo FinalChangeSearch::$final_change_type[FinalChangeSearch::FINAL_CHANGE_TYPE_RECHARGE]?></option>
                <option <?php if($param['change_type']==FinalChangeSearch::FINAL_CHANGE_TYPE_BUYNUMBER){echo 'selected';}?> value="<?= FinalChangeSearch::FINAL_CHANGE_TYPE_BUYNUMBER ?>"><?php echo FinalChangeSearch::$final_change_type[FinalChangeSearch::FINAL_CHANGE_TYPE_BUYNUMBER]?></option>
                <option <?php if($param['change_type']==FinalChangeSearch::FINAL_CHANGE_TYPE_CALLNUMBER){echo 'selected';}?> value="<?= FinalChangeSearch::FINAL_CHANGE_TYPE_CALLNUMBER ?>"><?php echo FinalChangeSearch::$final_change_type[FinalChangeSearch::FINAL_CHANGE_TYPE_CALLNUMBER]?></option>

            </select>
        </div>
        <div class=" col-lg-4 form-group">
            <?= Html::submitButton( Yii::t('app/harassment','Search'), ['class' => 'btn btn-primary index-button-1','id'=>'search_hide']) ?>
       </div>
       </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

