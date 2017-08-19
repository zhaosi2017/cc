<?php

$this->title =   Yii::t('app/nav','Quick payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','account center'), 'url' => ['recharge']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;

?>

<style>
    .loading{
        width: 100%;
        height: 100%;
        position: fixed;
        display: none;
        top: 0;
        left: 0;
        line-height: 56px;
        color: #fff;
        font-size: 15px;
        background:#000 url("/img/loading.gif") no-repeat center;
        opacity: 0.3;
        z-index: 99999999999;
        filter:progid:DXImageTransform.Microsoft.Alpha(opacity=30);
    }
    .loadDivShow{
        display: block !important;
    }
</style>
<div>

    <div class="user-form">
        <form id="payForm" class="m-t text-left" action="/home/account/pay" method="post">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
           <div class="form-group ">
               <label class="col-sm-1 text-right" style="font-size: 14px;padding-top: 5px;padding-right:2px;" for="amount"><?= Yii::t('app/account/index','Amount(¥)')?></label>
               <div class="col-sm-3">
                    <input  class="form-control" id="amount" type="number" name="amount" min="1" step="0.1" value="1">
                    <p id="amountmsg" style="font-size: 14px !important;color: #a94442;;">
                        <?php echo Yii::$app->session->hasFlash('pay_amount')?Yii::$app->session->getFlash('pay_amount'):'';?>
                    </p>
               </div>
               <div class="col-sm-8"></div>
           </div>
            <div class="col-sm-12"></div>
            <div class="form-group">
                <label class="col-sm-1 text-right" style="font-size: 14px;padding-top: 5px;padding-right:2px;" ><?= Yii::t('app/account/index','Recharge type')?></label>
                <div class="col-sm-3">
                    <?php $tmps = 0;?>
                    <?php foreach ($type as $t=> $v){?>


                        <div>
                            <img style="width: 40px;" src="/img/pay/<?php echo $v.'.jpg';?>" alt="">
                            <input type="radio" <?php if($tmps==0){echo 'checked';}?> name="order_type" value="<?php echo $t;?>"><?php echo $v;?>
                        </div>
                        <?php $tmps +=1; ?>
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
                    <span id = "rechage_buy"  onclick="payClick()" style="width: 100%;" data-loading-text="..." class='btn btn-primary btn-check button-new-color ' style="    width: 23%;margin-left: 128px;" >
                        <?= Yii::t('app/account/index','Recharge')?>                      </span>
                </div>
                <div class="col-sm-3"></div>
            </div>

        </form>
    </div>


</div>
<div id="loadDiv"  class="loading text-center" > </div>
<script>
    function payClick() {
        $("#amountmsg").html('');
        $("#order_type_msg").html('');
        var amount = $('#amount').val();
        var type =  $("input[name='order_type']:checked").val();
        if(amount == '' || amount <=0)
        {
            $("#amountmsg").html('<?= Yii::t('app/account/index','Please enter the correct amount')?>');
            return false;
        }
        if(type == undefined )
        {
            $("#order_type_msg").html('<?= Yii::t('app/account/index','Please choose the type of payment')?>');
            return false
        }

        $('#loadDiv').addClass('loadDivShow');
        setTimeout(function() {
            $('#payForm').submit();
        }, 1);
        return false;
    }
</script>