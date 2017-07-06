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
<script src="/js/global/bootstrap-switch.min.js?v=4.1.0"></script>
<link rel="stylesheet" href="/css/global/bootstrap-switch.min.css?v=4.1.">

<div class="call-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
    ]); ?>
    <div class="row">
        <div class="col-sm-4">
            <div style="display: inline-block">
                <?= Yii::t('app/harassment','Whitelist status')?> :
            </div>
            <div  style="display: inline-block">
                <div class="switch "   data-on="yes" data-off="no">
                    <input   id="ff" type="checkbox"  <?=   isset($identity->whitelist_switch) && $identity->whitelist_switch ? 'checked':''; ?>  />
                </div>
            </div>
        </div>
        <div class="col-sm-8">
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
                    <?= Html::submitButton(Yii::t('app/harassment','Search'), ['class' => 'btn btn-primary m-t-n-xs button-new-color','id'=>'search']) ?>
                </div>
            </div>
        </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script>

    $("#ff").bootstrapSwitch({

        size:"large",
        handleWidth:"70",
        onColor:"success",
        offColor:"info",
        animate:"true",
        onText: "<?= Yii::t('app/harassment','On')?>",
        offText: "<?= Yii::t('app/harassment','Off')?>",


        onSwitchChange:function(event,state){
            if(state==true)
            {
                status = 1;
            }else{
                status=0;
            }
            data = {};
            data.status = status;
            console.log(status);
            $.post('/home/white-list/update',data).done(function (r) {

                console.log(r);

            });


        },



    })
    ;

</script>