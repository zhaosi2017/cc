<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\assets\GlobalAsset;

GlobalAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title><?= $this->title ?></title>

    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

<!--    <link rel="shortcut icon" href="favicon.ico">-->
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>
</head>

<body class="fixed-sidebar full-height-layout gray-bg pace-done" style="overflow:hidden">
<audio class="hide" id="notice-ysp" src="<?= Yii::$app->homeUrl.'/media/yisell_sound_2014040216575424653_88366.mp3' ?>" preload="auto"></audio>
<?php $this->beginBody() ?>
    <?= isset($content) ? $content : ''  ?>
</body>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
