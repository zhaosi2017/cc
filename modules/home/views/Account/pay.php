<?php
/**
 * Created by PhpStorm.
 * User: pengzhang
 * Date: 2017/8/15
 * Time: 下午4:21
 */
$this->title =   Yii::t('app/nav','Quick payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;

?>

<div>

    <div class="user-form">
        <form class="m-t text-left" action="/home/account/pay" method="post">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
           <div class="form-group ">
               <label class="col-sm-1 text-right" style="font-size: 17px;padding-top: 5px;padding-right:2px;" for="amount">金额</label>
               <div class="col-sm-3">
                    <input  class="form-control" id="amount" type="number" name="amount" min="0" >
               </div>
               <div class="col-sm-8"></div>
           </div>
            <div class="col-sm-12"></div>
            <div class="form-group">
                <label class="col-sm-1 text-right" style="font-size: 17px;padding-top: 5px;padding-right:2px;" >支付类型</label>
                <div class="col-sm-3">
                    <?php foreach ($type as $t=> $v){?>
                        <div>
                            <img style="width: 40px;" src="/img/pay/<?php echo $v.'.jpg';?>" alt="">
                            <input type="radio" name="order_type" value="<?php echo $t;?>"><?php echo $v;?>
                        </div>
                    <?php }?>
                </div>
                <div class="col-sm-8"></div>


            </div>
            <div class="col-sm-12"></div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-3" >
                    <button style="width: 100%;" class='btn btn-primary button-new-color ' style="    width: 23%;margin-left: 128px;" >
                        充值                        </button>
                </div>
                <div class="col-sm-3"></div>
            </div>

        </form>
    </div>


</div>
