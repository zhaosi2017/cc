<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app/user/app-build' ,'Bind App for Communication');
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <div class="text-center" style="margin-bottom: 100px; ">
       <span style="margin: auto;font-size: 12px;font-weight: 600;"><?= Yii::t('app/user/app-build' , 'Bind a telegram or Potato account ,Enable offline call alerts . So that others can call you, but also allows you to call someone else')?></span>
    </div>
    <div>
        <div class="row app-bind-div" style="margin-top: 20px;border-bottom: 1px solid rgb(217,217,217); ">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/app-build', 'Bind Potato')?></div>
            <div class="col-xs-4">
                <?php echo  ($model->potato_number) ? $model->potato_number: '<span style="color:rgb(255,102,0);">'.
                    Yii::t('app/user/app-build','No Potato account')
                    .'</span>';?>
            </div>
            <div class="col-xs-4">
                <a href="/home/potato/bind-potato" style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"> <?php echo ($model->potato_number)?
                        Yii::t('app/user/app-build','Edit')
                        : Yii::t('app/user/app-build','Build');?></a>
                <?php if ($model->potato_number){?>
                <a  style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px" href="<?php echo Url::to(['/home/potato/unbundle-potato']) ?>" data-method="post" data-confirm="<?= Yii::t('app/user/app-build' , 'Are you sure you want to lift the binding?')?>" >

                    <?= Yii::t('app/user/app-build','lift the binding') ?>
                </a>
                <?php }?>
            </div>
        </div>
    </div>
    <div>
        <div class="row app-bind-div">
            <div class="col-xs-4 app-bind-1"><?= Yii::t('app/user/app-build', 'Bind Telegram')?></div>
            <div class="col-xs-4">
                <?php echo  ($model->telegram_number) ? $model->telegram_number: '<span style="color:rgb(255,102,0);">'.

                    Yii::t('app/user/app-build','No Telegram account')
                    .'</span>';?>

            </div>
            <div class="col-xs-4">
                <a href="/home/telegram/bind-telegram" style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"> <?php echo ($model->telegram_number)?
                        Yii::t('app/user/app-build','Edit')
                        : Yii::t('app/user/app-build','Build'); ?>
                </a>
               <?php  if($model->telegram_number) {?>
                <a href="<?php echo Url::to(['/home/telegram/unbundle-telegram']) ?>" data-method="post" data-confirm="<?= Yii::t('app/user/app-build' , 'Are you sure you want to lift the binding?')?>"  style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px">
                    <?= Yii::t('app/user/app-build','lift the binding') ?>
                </a>
                <?php }?>
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