<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/user/index','Base Info');
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
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index' ,'Bind CellPhone Number')?></div>
            <div class="col-xs-4">

                <?php echo  ($model->country_code.$model->phone_number) ? $model->country_code.'--'.$model->phone_number: '<span style="color:rgb(255,102,0);">'.
                Yii::t('app/user/index','No Phone NUmber').'</span>';?>

            </div>
            <div class="col-xs-4">
                <?php if ($model->phone_number){?>
                    <a href="/home/user/update-phone-number" style="    padding: 4px 18px;
    background-color: rgb(22,155,214);
    border-radius: 5px;
color: white;
position: relative;
    top: -3px;
    left: 31px
"><?= Yii::t('app/user/index', 'Edit')?></a>
                <?php }else{?>
                    <a href="/home/user/set-phone-number" style="    padding: 4px 18px;
    background-color: rgb(22,155,214);
    border-radius: 5px;
color: white;
position: relative;
    top: -3px;
    left: 31px
"> <?= Yii::t('app/user/index' , 'Build Now')?></a>
                <?php }?>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index', 'Bind Email') ?></div>
            <div class="col-xs-4">
                <?php echo  ($model->account) ? $model->account: '<span style="color:rgb(255,102,0);">'.
                    Yii::t('app/user/index','No Email')

                    .'</span>';?>

            </div>
            <div class="col-xs-4">
                <a  style="    padding: 4px 18px;
    background-color: rgb(22,155,214);
    border-radius: 5px;
color: white;
position: relative;
    top: -3px;
    left: 31px
"href="/home/user/bind-email"> <?php echo ($model->telegram_country_code.$model->telegram_number)?
                                                                        Yii::t('app/user/index', 'Edit')
                                                                        : Yii::t('app/user/index' , 'Build Now');?></a>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index' , 'Create UserName') ?></div>
            <div class="col-xs-4">
                <?php echo  ($model->username) ? $model->username: '<span style="color:rgb(255,102,0);">'.
                    Yii::t('app/user/index','No UserName')
                .'</span>';?>

            </div>
            <div class="col-xs-4">
                <a href="/home/user/bind-username" style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px">
                    <?php echo ($model->username)? Yii::t('app/user/index', 'Edit'):Yii::t('app/user/index' , 'Build Now');?></a>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index', 'Password') ?></div>
            <div class="col-xs-4">
               <spn>***************</spn>

            </div>
            <div class="col-xs-4">
                <a href="/home/user/password" style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"> <?= Yii::t('app/user/index', 'Edit')?></a>
            </div>
        </div>
    </div>

    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index', 'Language') ?></div>
            <div class="col-xs-4">
                <?php echo  ($model->language);?>

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