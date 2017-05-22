<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
    ]); ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model,'start_date')->input('date',['prompt'=>'请选择'])->label('注册时间：') ?>
            至
            <?= $form->field($model,'end_date')->input('date',['prompt'=>'请选择'])->label(false) ?>

            <a class="btn btn-xs btn-danger" onclick="
                $('#usersearch-start_date').val('');
                $('#usersearch-end_date').val('');
            ">清除时间</a>
        </div>
        <div class="col-lg-6">
            <div class="text-right no-padding">
                <?= $form->field($model, 'search_type')->dropDownList([
                    1 => 'potato',
                    2 => 'telegram',
                    3 => '账号',
                    4 => '昵称',
                    5 => '联系电话',
                    /*6 => '紧急联系人',
                    7 => '紧急联系人电话',*/
                ])->label(false) ?>
                <?= $form->field($model, 'search_keywords')->textInput(['placeholder' => '请输入关键字查询'])->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('search', ['class' => 'hide','id'=>'search_hide']) ?>
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary m-t-n-xs','id'=>'search','onclick'=>'
                        $("#usersearch-start_date").val("");
                        $("#usersearch-end_date").val("");
                    ']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
