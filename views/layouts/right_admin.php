<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

?>
<?php $this->beginContent('@app/views/layouts/public_admin.php'); ?>
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
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>
                            <?= Html::encode($this->title) ?>
                        </h5>
                    </div>

                    <div class="ibox-content">
                        <?= isset($content) ? $content : '' ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>
