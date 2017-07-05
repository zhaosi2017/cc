<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('app/user/links','Contact information');
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <div class="text-center" >

    </div>
    <di>
        <div >
            <div class="">
                <span style=" font-size: 17px;font-weight: 500; padding: 4px 0px"><?= Yii::t('app/user/links', 'Personal Contact')?></span>
                <a style="    padding: 4px 18px;
    background-color: rgb(22,155,214);
    border-radius: 5px;
    color: white;
    position: relative;
    top: -3px;
    left: 55px
" href="/home/user/set-phone-number"><?= Yii::t('app/user/links', 'Build') ?></a>
            </div>
        </div>
    </di>
    <div>


        <?php if (!empty($userPhone)){
            foreach($userPhone as $k => $phone){?>

                <div class="row app-bind-div" style="margin-top: 20px;border-bottom: 1px solid rgb(217,217,217); ">
                    <div class="col-xs-4 "><?php echo Yii::t('app/user/links','Contact').' '.($k+1);?> </div>
                    <div class="col-xs-4">
                        <?php echo  $phone->phone_country_code.' -- '.$phone->user_phone_number;?>
            </div>
            <div class="col-xs-4">
                <a href="<?php echo Url::to(['/home/user/set-phone-number' ,'phone_number'=>$phone->user_phone_number]) ?>"
                   style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"
                ><?= Yii::t('app/user/links', 'Edit') ?></a>
                <a  href="<?php echo Url::to(['/home/user/delete-number', 'type'=>'phone_number', 'phone_number'=>$phone->user_phone_number , 'country_code'=>$phone->phone_country_code]) ?>" data-method="post"

                    data-confirm="<?= Yii::t('app/user/links', 'Are you sure you want to delete it?')?>"
                    style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"
                ><?= Yii::t('app/user/links', 'Remove') ?></a>
            </div>
            </div>
         <?php   }
        }?>



    </div>

    <di>
        <div style="margin-top: 50px;">
            <div class="">
                <span style="    font-size: 17px;
    font-weight: 500;"><?= Yii::t('app/user/links', 'Emergency Contact')?></span>
                <a style="    padding: 4px 18px;
    background-color: rgb(22,155,214);
    border-radius: 5px;
color: white;
position: relative;
    top: -3px;
    left: 31px
" href="/home/user/add-urgent-contact-person"><?= Yii::t('app/user/links', 'Build') ?></a>
            </div>
        </div>
    </di>
    <div>

        <?php if (!empty($urgentContact)){
        foreach($urgentContact as $i => $contact){?>

        <div class="row app-bind-div">
            <div class="col-xs-4 "><?php echo Yii::t('app/user/links','Contact ').' ' .($i+1);?> </div>
            <div class="col-xs-4">
                <?php echo  $contact->contact_country_code.'--'.$contact->contact_phone_number;?>

            </div>
            <div class="col-xs-4">
                <a href="<?php echo Url::to(['/home/user/add-urgent-contact-person', 'modify' => '1' , 'id'=>$contact->id]) ?>"
                   style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"
                ><?= Yii::t('app/user/links', 'Edit') ?></a>
                <a href="<?php echo Url::to(['/home/user/delete-urgent-contact-person', 'type'=>'1' , 'id'=>$contact->id]) ?>" data-method="post"
                   data-confirm="<?= Yii::t('app/user/links', 'Are you sure you want to delete it?')?>"
                   style="padding: 4px 18px;background-color: rgb(22,155,214);border-radius: 5px;color: white;position: relative;top: -3px;left: 31px"
                ><?= Yii::t('app/user/links', 'Remove') ?></a>

            </div>
        </div>
        <?php }}?>
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