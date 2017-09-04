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

    <meta name="keywords" content="<?= Yii::t('app/index','Call support center')?>">
    <meta name="description" content="<?= Yii::t('app/index','The most secure network voice call platform')?>">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

<!--    <link rel="shortcut icon" href="favicon.ico">-->
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>
</head>

<body class="fixed-sidebar full-height-layout gray-bg pace-done" style="">
<?php $this->beginBody() ?>
    <?= isset($content) ? $content : ''  ?>
</body>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
