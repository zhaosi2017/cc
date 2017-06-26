<?php

/* @var $model app\modules\home\models\LoginForm */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '忘记密码';
?>
<div class="text-center   animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>忘记密码</h3>

        <div class="row">
            <a href="/home/login/find-password-one"><div style="display: inline-block;">
                <img style="border: 1px solid rgb(229, 229, 229);border-bottom: 0px ;background-color: rgb(245,245,245);padding: 20px;" src="/img/find-email.png" alt="">
                <div style="border: 1px solid rgb(229, 229, 229);border-top:0px;padding: 24px;">
                <div><strong style="font-size: 15px;">邮箱验证</strong></div>
                <div>通过验证注册邮箱来重置密码</div>
                </div>
            </div>
            </a>

            <div style="display: inline-block;width: 100px;visibility: hidden;" ></div>
            <a href="/home/login/phone-find-password">
            <div style="display: inline-block;">
                <img style="border: 1px solid rgb(229, 229, 229);background-color: rgb(245,245,245);padding: 20px" src="/img/find-mobile.png" alt="">
                <div style="border: 1px solid rgb(229, 229, 229);border-top:0px;padding: 24px; ">
                    <div><strong style="font-size: 15px;">手机验证</strong></div>
                    <div>通过验证绑定手机来重置密码</div>
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
