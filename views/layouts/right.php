<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

?>
<?php $this->beginContent('@app/views/layouts/header.php'); ?>

    <?= $this->render('common') ?>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>
                            <?php
                            echo \yii\widgets\Breadcrumbs::widget([

                                'homeLink'=>false, // 若设置false 则 可以隐藏Home按钮
                                //'homeLink'=>['label' => '主 页','url' => Yii::$app->homeUrl.'home/'], // 若设置false 则 可以隐藏Home按钮
                                'itemTemplate'=>"<span>{link} > </span>",
                                'activeItemTemplate'=>"<span>{link}</span>",
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ])
                            ?>

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
