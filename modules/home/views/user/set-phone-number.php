<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '修改绑定电话';

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'set-nickname-form',
        'options'=>['class'=>'m-t text-left'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-10\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]); ?>

    <div class="row form-inline">
        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label">电话号码</label>
            </div>
        </div>
        <div class="col-sm-10">
            <!--<div class="form-group">
                +
                <input title="" id="country-code" class="form-control" size="5" type="text">
                <div class="help-block"></div>
            </div>-->
            <?php echo $form->field($model, 'country_code', [
                'template' => "{label}\n<div>&nbsp;+{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            ])->textInput(['size' => 5,'placeholder'=>'国码'])->label(false) ?>

            <?php echo $form->field($model, 'phone_number')->textInput(['placeholder' => '您的手机号码'])->label(false) ?>
<!--            <div class="help-block">&nbsp;&nbsp;&nbsp;*请输入您的国码，然后输入您的手机号码</div>-->
        </div>

        <div class="col-sm-2 text-right">
            <div class="form-group">
                <label for="task-customer-category" class="col-sm-12 control-label"> </label>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="form-group">
                &nbsp;&nbsp;
                <input title="" class="form-control" size="18" type="text" placeholder="填写验证码">
                <div class="help-block"></div>
            </div>
            <div class="form-group">
                <input type="button" id="count-down" class="form-control" onclick="
                    var duration = 5;
                    $('#count-down').attr('disabled','disabled');
                    var url = '<?php echo Url::to(['/home/user/send-short-message']); ?>';
                    var data = {
                        };

                    data.number = '+' + $('#country-code').val() + $('#user-phone_number').val();
                    $.post(url, data).done(function(r) {
                        console.log(r);
                    });

                    var countDown = function() {
                        if(duration>0){
                            $('#count-down').val(duration);
                            duration--;
                        }else{
                            window.clearInterval(dt);
                            $('#count-down').attr('disabled',false).val('获取验证码');
                        }
                    };
                    var dt = self.setInterval(countDown,1000);
                " value="获取验证码">
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="form-group m-b-lg">
        <div class="col-sm-6 col-sm-offset-2">
            <?= Html::submitButton('绑定', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
