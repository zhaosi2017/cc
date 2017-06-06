<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

//use yii\helpers\Html;

$this->title = '拒绝访问';
?>
<div class="site-error">

<!--    <h1>--><?php //echo Html::encode($this->title) ?><!--</h1>-->

    <div class="alert alert-danger">
        <p>您无权使用该功能！</p>
        <p>若有疑问，请联系相关负责人！</p>
    </div>

   <!-- <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.111111
    </p>-->

</div>
