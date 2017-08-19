<?php

$this->title =   Yii::t('app/nav','Quick payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;

use app\modules\home\models\FinalMerchantInfo;
?>


 <div class="middle-box" style="margin-top: 35px !important;">
     <div>
         <div class="form-group">
         <label class="col-sm-4" for="chargeid">
             <?= Yii::t('app/account/index','Recharge amount(¥)')?>
         </label>
             <div class="col-sm-7">
                <input class="form-control" readonly="readonly" type="text" id="chargeid" value="<?php echo $model['amount'];?>">
             </div>
             <div class="col-sm-1"></div>
         </div>
         <div class="col-sm-12"></div>
         <br>
         <div class="form-group">
             <label class="col-sm-4" for="chargeid">
                 <?= Yii::t('app/account/index','Rate')?>
             </label>
             <div class="col-sm-7">
                 <input class="form-control" readonly="readonly" type="text" id="chargeid" value="<?php echo $model['rate'];?>">
             </div>
             <div class="col-sm-1"></div>
         </div>
         <div class="col-sm-12"></div>
         <br>
         <div class="form-group">
             <label class="col-sm-4" for="chargeid">
                 <?= Yii::t('app/account/index','Real Amount($)')?>
             </label>
             <div class="col-sm-7">
                 <input class="form-control" readonly="readonly" type="text" id="chargeid" value="<?php echo $model['real_amount'];?>">
             </div>
             <div class="col-sm-1"></div>
         </div>
         <div class="col-sm-12"></div>
         <br>

         <div class="form-group" style="margin-top: 27px;">
         <label class="col-sm-4" for="chargetype">
             <?= Yii::t('app/account/index','Recharge type')?>
         </label>
             <div class="col-sm-7">
         <input class="form-control" readonly="readonly" type="text" id="chargetype" value="<?php $finaMerchanInfo = new FinalMerchantInfo(); echo $finaMerchanInfo->getChannelName($model['order_type']);?>">
             </div>
             <div class="col-sm-1"></div>
         </div>
     </div>
     <div class="col-sm-12"></div>
     <form id="payformid" target="_blank" action="<?php echo $model['uri'];?>" method="<?php echo $model['request_type'];?>">
         <?php if( !empty($model['data']) && $model['data']){?>
            <?php foreach ($model['data'] as $key => $val ){?>
                <input type="hidden" name="<?php echo $key;?>" value="<?php echo $val;?>">
         <?php }?>
          <?php }?>
<!--         //<div class="center">-->
            <div class="form-group" style="margin-top: 78px;">
                <div class="col-sm-4"></div>
                <div class="col-sm-7">
         <input class="btn  index-button-1"  style="color: white;width: 100%;" type="submit" value="<?= Yii::t('app/account/index','Confirm')?>">
                </div>
                <dvi class="col-sm-1"></dvi>
            </div>
         <!--         </div>-->
     </form>
 </div>

