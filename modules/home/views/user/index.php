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
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index' ,'Bind Phone Number')?></div>
            <div class="col-xs-4">

                <?php echo  ($model->country_code.$model->phone_number) ? $model->country_code.'--'.$model->phone_number: '<span style="color:rgb(255,102,0);">'.
                Yii::t('app/user/index','No Phone NUmber').'</span>';?>

            </div>
            <div class="col-xs-4">
                <?php if ($model->phone_number){?>
                    <a  class="index-button-1" href="/home/user/update-phone-number" style="    padding: 4px 18px;
   
color: white;

"><?= Yii::t('app/user/index', 'Edit')?></a>
                <?php }else{?>
<!--                    <a  class="index-button-1" href="/home/user/set-phone-number" style="    padding: 4px 18px;color: white;"> --><?//= Yii::t('app/user/index' , 'Build Now')?><!--</a>-->
                    <a  class="index-button-1" href="/home/user/new-phone-number" style="    padding: 4px 18px;color: white;"> <?= Yii::t('app/user/index' , 'Build Now')?></a>

                <?php }?>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index', 'Bind Email') ?></div>
            <div class="col-xs-4">
                <?php echo  ($model->email) ? $model->email: '<span style="color:rgb(255,102,0);">'.
                    Yii::t('app/user/index','No Email')

                    .'</span>';?>

            </div>
            <div class="col-xs-4">
                <a  class="index-button-1" style="    padding: 4px 18px;color: white;
" href="/home/user/bind-email"> <?php echo ($model->telegram_country_code.$model->telegram_number)?
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
                <a class="index-button-1" href="/home/user/bind-username" style="padding: 4px 18px;color: white;">
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
                <a  class="index-button-1" href="/home/user/password" style="padding: 4px 18px;color: white;"> <?= Yii::t('app/user/index', 'Edit')?></a>
            </div>
        </div>
    </div>

    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index', 'NickName') ?></div>
            <div class="col-xs-4">
                <?php echo  ($model->nickname);?>

            </div>
            <div class="col-xs-4">
                <a  class="index-button-1" href="/home/user/set-nickname" style="padding: 4px 18px;color: white;">
                    <?php echo ($model->nickname)? Yii::t('app/user/index', 'Edit'):Yii::t('app/user/index' , 'Build Now');?></a>
            </div>

        </div>
    </div>

    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/index', 'Voice Content') ?></div>
            <div class="col-xs-4">
                <?php echo  ($model->voice);?>

            </div>
            <div class="col-xs-4">
                <a  class="index-button-1" href="/home/user/set-voice-content" style="padding: 4px 18px;color: white;">
                    <?php echo ($model->voice)? Yii::t('app/user/index', 'Edit'): Yii::t('app/user/index' , 'Build Now');?></a>
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