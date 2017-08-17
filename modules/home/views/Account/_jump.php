<?php

$this->title =   Yii::t('app/nav','Quick payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;

use app\modules\home\models\FinalMerchantInfo;
?>


 <div class="middle-box">
     <div>
         <div class="form-group">
         <label class="col-sm-3" for="chargeid">
            充值金额
         </label>
             <div class="col-sm-7">
                <input class="form-control" readonly="readonly" type="text" id="chargeid" value="<?php echo $model['amount'];?>">
             </div>
             <div class="col-sm-2"></div>
         </div>
         <div class="col-sm-12"></div>
         <br>

         <div class="form-group" style="margin-top: 27px;">
         <label class="col-sm-3" for="chargetype">
             充值类型
         </label>
             <div class="col-sm-7">
         <input class="form-control" readonly="readonly" type="text" id="chargetype" value="<?php $finaMerchanInfo = new FinalMerchantInfo(); echo $finaMerchanInfo->getChannelName($model['order_type']);?>">
             </div>
             <div class="col-sm-2"></div>
         </div>
     </div>
     <div class="col-sm-12"></div>
     <form id="payformid" target="_blank" action="<?php echo $model['Uri'];?>" method="<?php echo $model['request_type'];?>">
         <?php if( !empty($model['data']) && $model['data']){?>
            <?php foreach ($model['data'] as $key => $val ){?>
                <input type="hidden" name="<?php echo $key;?>" value="<?php echo $val;?>">
         <?php }?>
          <?php }?>
<!--         //<div class="center">-->
            <div class="form-group" style="margin-top: 78px;">
                <div class="col-sm-3"></div>
                <div class="col-sm-7">
         <input class="btn  index-button-1"  style="color: white;width: 100%;" type="submit" value="确认">
                </div>
                <dvi class="col-sm-2"></dvi>
            </div>
         <!--         </div>-->
     </form>
 </div>

