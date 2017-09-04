<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

?>
<?php $this->beginContent('@app/views/layouts/public.php'); ?>
 <?= $this->render('common') ?>
    <div class="wrapper wrapper-content">
        <?= isset($content) ? $content : '' ?>
    </div>

<?php $this->endContent(); ?>
