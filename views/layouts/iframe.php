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

    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

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
