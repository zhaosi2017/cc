<?php
use yii\helpers\Html;

use app\assets\FormAsset;
use yii\bootstrap\Alert;

FormAsset::register($this);
$this->beginPage()
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title><?= Yii::t('app/index','Call support center')?></title>

    <meta name="keywords" content="<?= Yii::t('app/index','Call support center')?>">
    <meta name="description" content="<?= Yii::t('app/index','The most secure network voice call platform')?>">



    <?php echo Html::csrfMetaTags() ?>

    <?php
        $this->head() ;
    ?>
</head>

<body class="fixed-sidebar full-height-layout gray-bg">
 <?= $this->render('common') ?>
<?php $this->beginBody() ?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <h5>
                        <?php echo Html::encode($this->title) ?>
                    </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    <?php echo isset($content) ? $content : "" ?>
                </div>

            </div>
        </div>
    </div>
</div>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
