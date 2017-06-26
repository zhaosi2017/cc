<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\home\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = '登录';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">&nbsp;</h1>
        </div>
        <h3>登录</h3>


        <div>

            <!-- Nav tabs -->
<!--            <ul class="nav nav-tabs" role="tablist">-->
<!--                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">邮箱</a></li>-->
<!--                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">电话</a></li>-->
<!--                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">用户名</a></li>-->
<!--            </ul>-->


<!--      --------------------------------------------------------邮箱--------------------------------------------------      -->

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">

                    <?php $form = ActiveForm::begin([
                        'id' => 'register-form',
                        'options'=>['class'=>'m-t text-left'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                        ],
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder'=>'邮箱／电话／用户名',
                    ])->label(false) ?>

                    <?= $form->field($model, 'pwd')->passwordInput(['placeholder'=>'密码'])->label(false) ?>



                    <?= $form->field($model, 'code')
                        ->label(false)
                        ->widget(Captcha::className(), [
                            'captchaAction'=>'/home/login/captcha',
                            'template' => '<div class="row"><div style="height:30px;line-height:33px;display: inline-block;width: 120px;margin-left: 14px;" >{input}</div><div style="display: inline-block;margin-left: 95px;">{image}</div></div>',
                            'options' => ['placeholder'=>'验证码']
                        ])



                    ?>

                    <?php
                    //调整验证码输入框的框度和内容的左边距
                    echo ' <style type="text/css">
            #loginform-code{
                width: 200px;
                padding-left: 10px;
            }
        </style>';

                    ?>



                    <?= Html::submitButton('登 录', ['class' => 'btn btn-primary block full-width m-b']) ?>

                    <?php ActiveForm::end(); ?>


                </div>


                <!--      --------------------------------------------------------电话--------------------------------------------------      -->

                <div role="tabpanel" class="tab-pane" id="profile">

                    <?php $form = ActiveForm::begin([
                        'id' => 'register-form',
                        'options'=>['class'=>'m-t text-left'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                        ],
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder'=>'邮箱账号',
                    ])->label(false) ?>

                    <?= $form->field($model, 'pwd')->passwordInput(['placeholder'=>'密码'])->label(false) ?>



                    <?= $form->field($model, 'code')
                        ->label(false)
                        ->widget(Captcha::className(), [
                            'captchaAction'=>'/home/login/captcha',
                            'template' => '<div class="row"><div style="height:30px;line-height:33px;display: inline-block;width: 120px;margin-left: 14px;" >{input}</div><div style="display: inline-block;margin-left: 95px;">{image}</div></div>',
                            'options' => ['placeholder'=>'验证码']
                        ])



                    ?>

                    <?php
                    //调整验证码输入框的框度和内容的左边距
                    echo ' <style type="text/css">
            #loginform-code{
                width: 200px;
                padding-left: 10px;
            }
        </style>';

                    ?>



                    <?= Html::submitButton('登 录', ['class' => 'btn btn-primary block full-width m-b']) ?>

                    <?php ActiveForm::end(); ?>



                </div>


                <!--      --------------------------------------------------------用户名--------------------------------------------------      -->
                <div role="tabpanel" class="tab-pane" id="messages">

                    <?php $form = ActiveForm::begin([
                        'id' => 'register-form',
                        'options'=>['class'=>'m-t text-left'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div>{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                        ],
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder'=>'邮箱账号',
                    ])->label(false) ?>

                    <?= $form->field($model, 'pwd')->passwordInput(['placeholder'=>'密码'])->label(false) ?>



                    <?= $form->field($model, 'code')
                        ->label(false)
                        ->widget(Captcha::className(), [
                            'captchaAction'=>'/home/login/captcha',
                            'template' => '<div class="row"><div style="height:30px;line-height:33px;display: inline-block;width: 120px;margin-left: 14px;" >{input}</div><div style="display: inline-block;margin-left: 95px;">{image}</div></div>',
                            'options' => ['placeholder'=>'验证码']
                        ])



                    ?>

                    <?php
                    //调整验证码输入框的框度和内容的左边距
                    echo ' <style type="text/css">
            #loginform-code{
                width: 200px;
                padding-left: 10px;
            }
        </style>';

                    ?>



                    <?= Html::submitButton('登 录', ['class' => 'btn btn-primary block full-width m-b']) ?>

                    <?php ActiveForm::end(); ?>


                </div>

            </div>
        </div>


        <p class="text-muted text-center">
            <a href="<?php echo \yii\helpers\Url::to(['/home/login/forget-password']) ?>"><small>忘记密码了？</small></a> | <a href="<?php echo \yii\helpers\Url::to(['/home/register/index']) ?>">注册一个新账号</a>
        </p>
    </div>
</div>