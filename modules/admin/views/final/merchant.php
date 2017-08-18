<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\home\servers\FinalService\aiyi;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '添加账号';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">

    <?php $form = ActiveForm::begin([
        'id' => 'add-merchant-form',
        'action'=>'merchant',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
          'labelOptions' => [],
        ],
    ]); ?>

    <?= $form->field($model , 'id')->textInput()->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'merchant_id')->textInput([
        'placeholder'=>'账号',
    ])->label('账号: ') ?>

    <?= $form->field($model, 'recharge_type')
             ->checkboxList(aiyi::$service_name_map ,
                            ['item'=>function($index,$text,$name , $c, $v) use ($model){

                                foreach (aiyi::$service_name_map as $key=>$value){
                                    if($key == $v){
                                        if( $model->recharge_type & $v){
                                            $checked = 'checked';
                                        }else{
                                            $checked ='';
                                        }
                                        return '<input type="checkbox"  name="'.$name.'" value="'.$v.'" '.$checked.' > '.$text;
                                    }
                                }
                            } ])
             ->label('支付类型') ?>


    <?= $form->field($model, 'sign_type')
        ->dropDownList(['1'=>"md5" , "2"=>"rsa"])
        ->label('加密类型')
        ?>
    <?= $form->field($model, 'status')
        ->dropDownList(['1'=>"启用" , '2'=>"不启用"])
        ->label('启用状态')
    ?>
    <?= $form->field($model, 'certificate')
        ->textInput([
            'placeholder'=>'key',
        ])
        ->label('签名key')
    ?>
    <?= $form->field($model, 'amount')
        ->textInput([
            'placeholder'=>'金额',
        ])
        ->label('余额')
    ?>

    <?= Html::submitButton('提 交', ['class' => 'btn btn-primary block full-width m-b']) ?>

    <?php ActiveForm::end(); ?>
</div>
