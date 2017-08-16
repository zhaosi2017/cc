<?php

$this->title =   Yii::t('app/nav','Quick payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','User center'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;

?>

<div>

    <div class="user-form">
        <form id="payform" class="m-t text-left" action="/home/account/pay" method="post">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
           <div class="form-group ">
               <label class="col-sm-1 text-right" style="font-size: 14px;padding-top: 5px;padding-right:2px;" for="amount">金额</label>
               <div class="col-sm-3">
                    <input  class="form-control" id="amount" type="number" name="amount" min="0" step="0.1" >
                    <p id="amountmsg" style="font-size: 14px !important;color: #a94442;;">
                        <?php echo Yii::$app->session->hasFlash('pay_amount')?Yii::$app->session->getFlash('pay_amount'):'';?>
                    </p>
               </div>
               <div class="col-sm-8"></div>
           </div>
            <div class="col-sm-12"></div>
            <div class="form-group">
                <label class="col-sm-1 text-right" style="font-size: 14px;padding-top: 5px;padding-right:2px;" >支付类型</label>
                <div class="col-sm-3">
                    <?php foreach ($type as $t=> $v){?>
                        <div>
                            <img style="width: 40px;" src="/img/pay/<?php echo $v.'.jpg';?>" alt="">
                            <input type="radio" name="order_type" value="<?php echo $t;?>"><?php echo $v;?>
                        </div>
                    <?php }?>
                    <p id="order_type_msg" style="font-size: 14px !important;color: #a94442;">
                        <?php echo Yii::$app->session->hasFlash('pay_order_type')?Yii::$app->session->getFlash('pay_order_type'):'';?>

                    </p>
                </div>
                <div class="col-sm-8"></div>


            </div>
            <div class="col-sm-12"></div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-3" >
                    <span onclick="payClick()" style="width: 100%;" class='btn btn-primary button-new-color ' style="    width: 23%;margin-left: 128px;" >
                        充值                        </span>
                </div>
                <div class="col-sm-3"></div>
            </div>

        </form>
    </div>


</div>

<script>
    function payClick() {
        $("#amountmsg").html('');
        $("#order_type_msg").html('');
        var amount = $('#amount').val();
        var type =  $("input[name='order_type']:checked").val();
        if(amount == '' || amount <=0)
        {
            $("#amountmsg").html('请输入正确的金额');
            return false;
        }
        if(type == undefined )
        {
            $("#order_type_msg").html('请选择支付类型');
            return false
        }

        $("#payform").submit();
        return false;
    }
</script>