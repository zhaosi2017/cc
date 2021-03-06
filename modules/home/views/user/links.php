<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('app/user/links','My contact information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\modules\home\models\ContactForm */
/* @var $form yii\widgets\ActiveForm */
?>


    <div class="second-nav-div-3">
        <ul class="nav nav-tabs" >
            <li  class="active"><a  data-toggle="pill" href="#tab1" ><?= Yii::t('app/user/links','Personal Contact')?></a></li>
            <li  ><a  data-toggle="pill" href="#tab2"><?= Yii::t('app/user/links','Emergency Contact')?></a></li>
        </ul>
    </div>

<div class="tab-content">
    <div id="tab1" class="tab-pane fade in active">
        <div style="margin-top: 50px;">
            <div >
                <div class="">
                    <span style=" font-size: 17px;font-weight: 500; padding: 4px 0px"><?= Yii::t('app/user/links', 'Personal Contact')?></span>
                    <a class="index-button-1" style=" padding: 4px 18px;color: white;position: relative;left: 31px" href="/home/user/set-phone-number"><?= Yii::t('app/user/links', 'Build') ?></a>
                </div>
            </div>
        </div>
        <div>
            <?php if (!empty($userPhone)){
                foreach($userPhone as $k => $phone){?>

                    <div class="row app-bind-div" style="margin-top: 20px;border-bottom: 1px solid rgb(217,217,217); ">
                        <div class="col-xs-4 "><?php echo Yii::t('app/user/links','Contact').' '.($k+1);?> </div>

                        <div class="col-xs-4">
                            <?php $t = $phone->phone_country_code ; if (isset($t[0])){ $t[0]=='+'? $t :$t = '+'.$t;}  echo  $t.'-'.$phone->user_phone_number;?>
                        </div>
                        <div class="col-xs-4">
                            <a class="index-button-1"  href="<?php echo Url::to(['/home/user/set-phone-number' ,'phone_number'=>$phone->user_phone_number]) ?>"
                               style="padding: 4px 18px;color: white;"
                            ><?= Yii::t('app/user/links', 'Edit') ?></a>
                            <a class="index-button-1"   href="<?php echo Url::to(['/home/user/delete-number', 'type'=>'phone_number', 'phone_number'=>$phone->user_phone_number , 'country_code'=>$phone->phone_country_code]) ?>" data-method="post"

                                data-confirm="<?= Yii::t('app/user/links', 'Are you sure you want to delete it?')?>"
                                style="padding: 4px 18px;color: white;"
                            ><?= Yii::t('app/user/links', 'Remove') ?></a>
                        </div>
                    </div>
                <?php   }
            }?>
        </div>
    </div>
    <div id="tab2" class="tab-pane fade">
        <div>
            <div style="margin-top: 50px;">
                <div class="">
                    <span style="    font-size: 17px;font-weight: 500;"><?= Yii::t('app/user/links', 'Emergency Contact')?></span>
                    <a  class="index-button-1" style="    padding: 4px 18px;color: white;position: relative;left: 31px" href="/home/user/add-urgent-contact-person"><?= Yii::t('app/user/links', 'Build') ?></a>
                </div>
            </div>
        </div>
        <div>

            <?php if (!empty($urgentContact)){
                foreach($urgentContact as $i => $contact){?>

                    <div class="row app-bind-div">
                        <div class="col-xs-3 "><?php echo Yii::t('app/user/links','Contact ').' ' .($i+1);?> </div>
                        <div class="col-xs-3"><?php  echo $contact->contact_nickname; ?></div>

                        <div class="col-xs-2">
                            <?php $p = $contact->contact_country_code ; if (isset($p[0])){ $p[0]=='+'? $p :$p = '+'.$p;} echo  $p.'-'.$contact->contact_phone_number;?>

                        </div>
                        <div class="col-xs-4">
                            <a class="index-button-1"  href="<?php echo Url::to(['/home/user/add-urgent-contact-person', 'modify' => '1' , 'id'=>$contact->id]) ?>"
                               style="padding: 4px 18px;color: white;"
                            ><?= Yii::t('app/user/links', 'Edit') ?></a>
                            <a class="index-button-1"  href="<?php echo Url::to(['/home/user/delete-urgent-contact-person', 'type'=>'1' , 'id'=>$contact->id]) ?>" data-method="post"
                               data-confirm="<?= Yii::t('app/user/links', 'Are you sure you want to delete it?')?>"
                               style="padding: 4px 18px;color: white;"
                            ><?= Yii::t('app/user/links', 'Remove') ?></a>

                        </div>
                    </div>
                <?php }}?>
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









