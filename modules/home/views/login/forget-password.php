<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app/login','Forget password');
?>
<div class="text-center   animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3><?= Yii::t('app/login','Forget password')?></h3>

        <div class="row">
            <a href="/home/login/find-password-one"><div style="display: inline-block;">
                <img style="border: 1px solid rgb(229, 229, 229);border-bottom: 0px ;background-color: rgb(245,245,245);padding: 20px;" src="/img/find-email.png" alt="">
                <div style="border: 1px solid rgb(229, 229, 229);border-top:0px;padding: 24px;width: 242px;">
                <div><strong style="font-size: 15px;"><strong><?= Yii::t('app/login','Email verification')?></strong></div>
                <div><?= Yii::t('app/login','Reset the password by verifying the registered email')?></div>
                </div>
            </div>
            </a>

            <div style="display: inline-block;width: 100px;visibility: hidden;" ></div>
            <a href="/home/login/phone-find-password">
            <div style="display: inline-block;">
                <img style="border: 1px solid rgb(229, 229, 229);background-color: rgb(245,245,245);padding: 20px" src="/img/find-mobile.png" alt="">
                <div style="border: 1px solid rgb(229, 229, 229);border-top:0px;padding: 24px;width: 242px; ">
                    <div><strong style="font-size: 15px;"><?= Yii::t('app/login','Phone verification')?></strong></div>
                    <div><?= Yii::t('app/login','Resets the password by verifying the binding phone')?></div>
                </div>
            </div>
            </a>
        </div>

    </div>
</div>


<style>
    body{
        background-color: rgb(255,255,255) !important;
    }
</style>
