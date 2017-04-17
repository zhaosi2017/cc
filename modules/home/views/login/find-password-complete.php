<?php

$this->title = '找回密码成功';
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3>找回密码成功</h3>
        <blockquote class="text-center">
            2秒后跳转至登录页面
        </blockquote>
    </div>
</div>
<script>
    setTimeout(function () {
        window.location.href = '<?php echo \yii\helpers\Url::to(['/home/login/index']) ?>';
    },2000)
</script>



