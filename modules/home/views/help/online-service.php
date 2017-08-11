<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app/nav','Online service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Help Center'), 'url' => ['guide']];
$this->params['breadcrumbs'][] = $this->title;
$userid = !Yii::$app->user->isGuest ? Yii::$app->user->id:rand(1,99999).microtime(true);
?>
<style>

    #msg-contailer{
        width:600px;
        height:400px;
        border: 1px solid rgb(56,181,231);
        background-color: white;
        overflow-y: auto;
        margin-top: 2px;
    }
    #msgContent{
        width: 400px !important;
        padding-left: 5px;
    }
    #msgSend{
        width: 400px;

    }
</style>
<script src="/js/home/jquery.js"></script>
<!-- Live800默认跟踪代码: 开始-->
<!--<script language="javascript" src="http://livechat.live800.com/livechat/chatClient/monitor.js?jid=1150614657&companyID=71156846&configID=126146&codeType=custom"></script>-->
<!-- Live800默认跟踪代码: 结束-->


<div>
    <div class="text-center">
<!--        <div><textarea name="" id="msgBox" cols="30" rows="10"></textarea></div>-->

        <div class="middle-box" id = "msg-contailer">
            <div id="msgBox">

            </div>
        </div>
        <div style="margin-top: 3px;"><input type="text" id="msgContent" placeholder="请输入"></div>
        <div style="margin-top: 3px;"></div>
        <div><span class="btn" id="msgSend" style="border: 1px solid rgb(56,181,231);    background-color: rgb(56,181,231);color: white">发送</span></div>
    </div>
</div>
<script>
    // var socket = new WebSocket('ws://0.0.0.0:9507');

    // // 打开Socket
    // socket.onopen = function(event) {

    //     socket.send(JSON.stringify({
    //         type: "login",
    //         userid: '<?php echo $userid?>'
    //     }));
    // }
    // // 监听消息
    // socket.onmessage = function(event) {
    //     data = $.parseJSON(event.data);
    //     console.log(data);
    //     if(data.userid == '<?php echo $userid?>')
    //     {
    //         $('#msgBox').append('<div class="text-right">'+data.userid+data.time+':'+data.msg+'</div>');
    //     }else{
    //         $('#msgBox').append('<div class="text-left">'+data.userid+data.time+':'+data.msg+'</div>');
    //     }


    // };

    // // 监听Socket的关闭
    // socket.onclose = function(event) {
    //     console.log(event);
    //     console.log('Client notified socket has closed',event);
    // };


    // function welcome() {
    //     $('#msgBox').text();
    // }

    // $('#msgSend').click(function () {
    //      var ms = $('#msgContent').val();
    //      if(ms == '' || ms == null)
    //      {
    //          alert('输入的内容不能为空');
    //          $('#msgContent').focus();
    //          return false;
    //      }
    //      socket.send(JSON.stringify({
    //          type: "say",
    //          data: ms,
    //          userid:'<?php echo $userid?>'
    //      }));
    //     $('#msgContent').val('');
    //     $('#msg-contailer').animate({scrollTop:$('#msgBox').outerHeight()+window.innerHeight},200);
    // })
</script>
