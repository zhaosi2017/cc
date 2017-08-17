<?php

$this->title =   Yii::t('app/nav','Quick payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;
use \app\modules\home\servers\FinalService\aiyi;
?>


 <div class="middle-box">
     <div>
         <label for="chargeid">
             <input type="text" id="chargeid" value="<?php echo $model['amount'];?>">
         </label>
         <label for="chargetype">
             <input type="text" id="chargetype" value="<?php echo aiyi::$service_map[$model['order_type']];?>">
         </label>
     </div>
     <form id="payformid" target="_blank" action="<?php echo $model['Uri'];?>" method="<?php echo $model['request_type'];?>">
         <?php if( !empty($model['data']) && $model['data']){?>
            <?php foreach ($model['data'] as $key => $val ){?>
                <input type="hidden" name="<?php echo $key;?>" value="<?php echo $val;?>">
         <?php }?>
          <?php }?>
         <div class="center">
         <input class="btn btn-primary index-button-1"  type="submit" value="确认">
         </div>
     </form>
 </div>

