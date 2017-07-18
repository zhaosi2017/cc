<?php
use yii\helpers\Html;

//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
//use yii\widgets\Breadcrumbs;
use app\assets\GlobalAsset;
//
GlobalAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>H+ 后台主题UI框架 - 主页</title>

    <meta name="keywords" content="<?= Yii::t('app/index','Call support center')?>">
    <meta name="description" content="<?= Yii::t('app/index','The most secure network voice call platform')?>">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

<!--    <link rel="shortcut icon" href="favicon.ico">-->
    <?= Html::csrfMetaTags() ?>

    <!--    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>-->
    <?php $this->head() ?>
</head>

<body class="gray-bg">
<?php $this->beginBody() ?>
<?= isset($content) ? $content :'' ?>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
