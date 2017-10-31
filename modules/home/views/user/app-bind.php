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


<div class="second-nav-div-3">
    <ul class="nav nav-tabs" >
        <li  class="active"><a  data-toggle="pill" href="#tab1" ><?= Yii::t('app/user/app-build', 'Bind Potato')?></a></li>
        <li  ><a  data-toggle="pill" href="#tab2"><?= Yii::t('app/user/app-build', 'Bind Telegram')?></a></li>
    </ul>
</div>

<div class="tab-content">
    <div id="tab1" class="tab-pane fade in active">
        <div style="margin-top: 50px;">
            <div >
                <div class="">
                    <span style=" font-size: 17px;font-weight: 500; padding: 4px 0px"><?= Yii::t('app/user/app-build', 'Bind Potato')?></span>
                    <a class="index-button-1" style=" padding: 4px 18px;color: white;position: relative;left: 31px" href="/home/potato/bind-potato"><?= Yii::t('app/user/links', 'Build') ?></a>
                </div>
            </div>
        </div>
        <div>
            <?php if (!empty($potato)){
                foreach($potato as $k => $phone){?>

                    <div class="row app-bind-div" style="margin-top: 20px;border-bottom: 1px solid rgb(217,217,217); ">
                        <div class="col-xs-4 "><?php echo 'potato '.($k+1);?> </div>

                        <div class="col-xs-4">
                            <?php $t = $phone->app_name ; if (isset($t[0])){ $t[0]=='+'? $t :$t = '+'.$t;}  echo  $t.'-'.$phone->app_number;?>
                        </div>
                        <div class="col-xs-4">
                            <a class="index-button-1"  href="<?php echo Url::to(['/home/potato/bind-potato' ,'id'=>$phone->id]) ?>"
                               style="padding: 4px 18px;color: white;"
                            ><?= Yii::t('app/user/links', 'Edit') ?></a>
                            <a class="index-button-1"   href="<?php echo Url::to(['/home/potato/unbundle-potato', 'id'=>$phone->id ]) ?>" data-method="post"

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
                    <span style="    font-size: 17px;font-weight: 500;"><?= Yii::t('app/user/app-build', 'Bind Telegram')?></span>
                    <a  class="index-button-1" style="    padding: 4px 18px;color: white;position: relative;left: 31px" href="/home/telegram/bind-telegram"><?= Yii::t('app/user/links', 'Build') ?></a>
                </div>
            </div>
        </div>
        <div>

            <?php if (!empty($telegram)){
                foreach($telegram as $i => $contact){?>

                    <div class="row app-bind-div">
                        <div class="col-xs-4 "><?php echo 'telegram ' .($i+1);?> </div>
<!--                        <div class="col-xs-3">--><?php // echo $contact->app_name; ?><!--</div>-->

                        <div class="col-xs-4">
                            <?php $p = $contact->app_name ; if (isset($p[0])){ $p[0]=='+'? $p :$p = '+'.$p;} echo  $p.'-'.$contact->app_number;?>

                        </div>
                        <div class="col-xs-4">
                            <a class="index-button-1"  href="<?php echo Url::to(['/home/telegram/bind-telegram' , 'id'=>$contact->id]) ?>"
                               style="padding: 4px 18px;color: white;"
                            ><?= Yii::t('app/user/links', 'Edit') ?></a>
                            <a class="index-button-1"  href="<?php echo Url::to(['/home/telegram/unbundle-telegram' , 'id'=>$contact->id]) ?>" data-method="post"
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









