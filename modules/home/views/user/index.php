<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '基本资料';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="user-form">
    <div class="text-center" >


    </div>
    <div>
        <div class="row app-bind-div" style="margin-top: 20px;border-bottom: 1px solid rgb(217,217,217); ">
            <div class="col-xs-4 app-bind-1">绑定手机</div>
            <div class="col-xs-4">

                <?php echo  ($model->country_code.$model->phone_number) ? $model->country_code.'--'.$model->phone_number: '<span style="color:rgb(255,102,0);">未绑定手机</span>';?>

            </div>
            <div class="col-xs-4">
                <?php if ($model->phone_number){?>
                    <a href="/home/user/update-phone-number">修改</a>
                <?php }else{?>
                    <a href="/home/user/set-phone-number"> 立即添加</a>
                <?php }?>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1">绑定邮箱</div>
            <div class="col-xs-4">
                <?php echo  ($model->account) ? $model->account: '<span style="color:rgb(255,102,0);">未绑定邮箱账号</span>';?>

            </div>
            <div class="col-xs-4">
                <a href="/home/user/bind-email"> <?php echo ($model->telegram_country_code.$model->telegram_number)? '修改':'立即绑定';?></a>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1">绑定用户名</div>
            <div class="col-xs-4">
                <?php echo  ($model->username) ? $model->username: '<span style="color:rgb(255,102,0);">未绑定用户名</span>';?>

            </div>
            <div class="col-xs-4">
                <a href="/home/user/bind-username"> <?php echo ($model->telegram_country_code.$model->telegram_number)? '修改':'立即绑定';?></a>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1">登录密码</div>
            <div class="col-xs-4">
               <spn>***************</spn>

            </div>
            <div class="col-xs-4">
                <a href="/home/user/password"> 修改</a>
            </div>
        </div>
    </div>

</div>


<style>
    .app-bind-1{
        font-size: 17px; !important;
        font-weight: 500;!important;
    }

    .app-bind-div{
        margin-top: 20px;
        border-bottom: 1px solid rgb(217,217,217);
        margin-bottom: 30px;
        padding-bottom: 20px;
        text-align: center;
    }
</style>