<?php

$this->title = Yii::t('app/login','Retrieve password successfully');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">&nbsp;</h1>

        </div>
        <h3><?= Yii::t('app/login','Retrieve password successfully')?></h3>
        <blockquote class="text-center">
            2<?= Yii::t('app/login','Seconds to jump to the login page')?>
        </blockquote>
    </div>
</div>
<script>
    setTimeout(function () {
        window.location.href = '<?php echo \yii\helpers\Url::to(['/home/login/login']) ?>';
    },2000)
</script>



