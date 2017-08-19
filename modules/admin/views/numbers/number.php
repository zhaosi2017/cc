<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\home\servers\FinalService\aiyi;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '添加号码';
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
?>
<div class="call-record-index">

    <?php $form = ActiveForm::begin([
        'id' => 'add-number-form',
        'action'=>'modify-number',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
            'labelOptions' => [],
        ],
    ]); ?>

    <?= $form->field($model , 'id')->textInput()->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'number')->textInput([

    ])->label('电话号码: ') ?>

    <?= $form->field($model, 'rent_status')
        ->dropDownList(\app\modules\admin\models\Numbers\CallNumber::$numStatusArr)
        ->label('外租状态')
    ?>
    <?= $form->field($model, 'status')
        ->dropDownList(\app\modules\admin\models\Numbers\CallNumber::$numRentStatusArr)
        ->label('可用状态')
    ?>
    <?= $form->field($model, 'comment')
        ->textInput([

        ])
        ->label('备注')
    ?>
    <?= $form->field($model, 'begin_time')
        ->textInput([

        ])
        ->label('有效时间起')
    ?>
    <?= $form->field($model, 'end_time')
        ->textInput([
        ])
        ->label('有效时间止')
    ?>
    <?= $form->field($model, 'price')
        ->textInput([
        ])
        ->label('外租价格／天')
    ?>
    <?= $form->field($model, 'interface')
        ->dropDownList(['nexmo'=>'nexmo' , 'sinch'=>'sinch' , 'infobip'=>'infobip'
        ])
        ->label('接口')
    ?>

    <?= Html::submitButton('提 交', ['class' => 'btn btn-primary block full-width m-b']) ?>

    <?php ActiveForm::end(); ?>
</div>
