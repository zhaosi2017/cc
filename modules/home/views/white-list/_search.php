<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecordSearch */
/* @var $form yii\widgets\ActiveForm */
$Ontext =  isset($userModels->whitelist_switch) && $userModels->whitelist_switch ? Yii::t('app/harassment','On'): Yii::t('app/harassment','Off');

$identity = Yii::$app->user->identity;

?>
<script src="/js/home/jquery.js"></script>

 <link href="/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="/js/home/bootstrap-toggle.min.js"></script>


<div class="call-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
    ]); ?>
    <div class="row">
        <div class="col-sm-3">
            <div style="display: inline-block">
                <?= Yii::t('app/harassment','Whitelist status')?> :
            </div>
            <div  style="display: inline-block">
                <input id="toggle-one" data-onstyle="info" data-offstyle="success" <?=  isset($identity->whitelist_switch) && $identity->whitelist_switch ? 'checked':''; ?> type="checkbox" data-on=" " /* data-on="<?= Yii::t('app/harassment','On')?>" */ data-off="<?= Yii::t('app/harassment','Off')?>">
            </div>
        </div>
        <div class="col-sm-9">
        <div class="col-lg-6">
            <div >
                 <?= $form->field($model, 'search_type')->dropDownList(
                [
                    //1 => Yii::t('app/harassment', 'Whitelist account'),
                    // 2 => '白名单电话',
                    2=>'telegram',
                    3=>'potato'
                ],
                ['prompt' => Yii::t('app/harassment','All')]
                )->label(false) ?>
                <?= $form->field($model, 'search_keywords')->textInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('search', ['class' => 'hide','id'=>'search_hide']) ?>
                    <?= Html::submitButton(Yii::t('app/harassment','Search'), ['class' => 'btn btn-primary m-t-n-xs button-new-color','id'=>'search','style'=>'    margin-bottom: 5px;']) ?>
                </div>
            </div>
        </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<script>

    $('#toggle-one').bootstrapToggle({
       
    });

     $('#toggle-one').change(function(state) {
        console.log(state.target.checked);
        data = {};
        if(state.target.checked){
            status = 1;
        }else{
            status = 0;
        }
        data.status = status;
        $.post('/home/white-list/update',data).done(function (r) {
                console.log(r);
           });
        
    });

</script>

