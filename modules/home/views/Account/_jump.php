<?php

?>



 <div>
     <form id="payformid" target="_blank" action="<?php echo $model['Uri'];?>" method="<?php echo $model['request_type'];?>">
         <?php if( !empty($model['data']) && $model['data']){?>
            <?php foreach ($model['data'] as $key => $val ){?>
                <input type="hidden" name="<?php echo $key;?>" value="<?php echo $val;?>">
         <?php }?>
          <?php }?>
         <input type="submit" value="确认">
     </form>
 </div>

