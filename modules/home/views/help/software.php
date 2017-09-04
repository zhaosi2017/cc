<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title =  Yii::t('app/nav','Introduction to the software');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Help Center'), 'url' => ['guide']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div >
    <div style="position: absolute;z-index: 1;color: black;margin-top: 100px;
    margin-left: 100px;">
        <div class="text-left" style="font-size: 50px";>
            <?= Yii::t('app/nav','Introduction to the software')?>
        </div>
        <div class=" text-left shouye-div-2">
            <?= Yii::t('app/index','The most secure network voice call platform')?>.
        </div>
        <div class=" text-left shouye-div-2">
            <?= Yii::t('app/index','Provide intelligent voice call reminder service for emergency matters')?>.
        </div>
        <div class=" text-left shouye-div-2">
            <?= Yii::t('app/index','In your communication app directly call the other phone, call each other in time to reply to you')?>.
        </div>
        <div class=" text-left shouye-div-2">
            <?= Yii::t('app/index','Make communication more efficient and convenient')?>.
        </div>
    </div>
    <img src="/img/shouye3.jpg" alt="" style="position:relative;width:100%;">

</div>
