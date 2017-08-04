<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app/nav','Online service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Help Center'), 'url' => ['guide']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <!-- Live800在线客服图标:默认图标[浮动图标] 开始-->
    <div style='display:none;'><a href='http://www.live800.com'>live800Link.customerservicesystem</a></div><script language="javascript" src="http://livechat.live800.com/livechat/chatClient/floatButton.js?jid=1150614657&companyID=71156846&configID=126147&codeType=custom"></script><div style='display:none;'><a href='http://en.live800.com'>live chat</a></div>
    <!-- 在线客服图标:默认图标 结束-->
</div>
<!-- Live800默认跟踪代码: 开始-->
<script language="javascript" src="http://livechat.live800.com/livechat/chatClient/monitor.js?jid=1150614657&companyID=71156846&configID=126146&codeType=custom"></script>
<!-- Live800默认跟踪代码: 结束-->
