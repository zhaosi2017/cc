<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

?>
<?php $this->beginContent('@app/views/layouts/public.php'); ?>
<?php
if( Yii::$app->getSession()->hasFlash('success') ) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success no-margins', //这里是提示框的class
            // 'style' => 'z-index:9999;position:fixed;width:100%',
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
}
if( Yii::$app->getSession()->hasFlash('error') ) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-warning no-margins',
        ],
        'body' => Yii::$app->getSession()->getFlash('error'),
    ]);
}
if( Yii::$app->getSession()->hasFlash('info') ) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-info no-margins',
        ],
        'body' => Yii::$app->getSession()->getFlash('info'),
    ]);
}
?>
    <div class="wrapper wrapper-content">
        <?= isset($content) ? $content : '' ?>
    </div>

<?php $this->endContent(); ?>
