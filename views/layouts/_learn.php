<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;
?>

<script src="/js/home/jquery.js"></script>
<script src="/js/global/bootstrap.min.js"></script>
<button  style="display: none" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    <!--    开始演示模态框-->
</button>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?= Yii::t('app/login','Please do the following')?>
                </h4>
            </div>
            <div class="modal-body text-center" >
                <?=  $message  ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>

            </div>
        </div>
    </div>
</div>
<script>
    $(function () { $('#myModal').modal({
        keyboard: true
    })});
</script>
<style type="text/css">
    /*.modal.in .modal-dialog{-webkit-transform:translate(0,-50%);-ms-transform:translate(0,-50%);-o-transform:translate(0,-50%);transform:translate(0,-50%)}*/
    /*.modal-dialog{position:absolute;width:auto;margin:10px auto;left:0;right:0;top:50%}*/
    .modal.in .modal-dialog{-webkit-transform:translate(0,50%);-ms-transform:translate(0,50%);-o-transform:translate(0,50%);transform:translate(0,50%)}
    .modal-dialog{position:absolute;width:auto;margin:10px auto;left:0;right:0;top:25%}


    @media (min-width:768px){.modal-dialog{width:600px}
</style>