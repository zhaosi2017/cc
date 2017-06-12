<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;

if( Yii::$app->getSession()->hasFlash('success') ) {
    echo Alert::widget([
        'options' => [
            // 'class' => 'alert-success no-margins', //这里是提示框的class
            // 'style' => 'z-index:9999;position:fixed;width:100%',
         'style' => '
                position: fixed;
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
                width: 30%;
                left: 40%;
                top: 30%;
                z-index: 99999999;
                height: 83px;
                text-align: center;
                line-height:62px;
                ',
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
     echo '<script type="text/javascript"  ?>
            function closeSuccess(){
                $(".alert").hide();
            }    
            t = setTimeout(closeSuccess,2000);
    </script>';
}
if( Yii::$app->getSession()->hasFlash('error') ) {
    echo Alert::widget([
        'options' => [
            // 'class' => 'alert-warning no-margins',
        'style' => '
                position: fixed;
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
                width: 30%;
                left: 40%;
                top: 30%;
                z-index: 99999999;
                height: 83px;
                text-align: center;
                line-height:62px;
                ',
        ],
        'body' => Yii::$app->getSession()->getFlash('error'),
    ]);
     echo '<script type="text/javascript"  ?>
            function closeSuccess(){
                $(".alert").hide();
            }    
            t = setTimeout(closeSuccess,2000);
    </script>';
}
if( Yii::$app->getSession()->hasFlash('info') ) {
    echo Alert::widget([
        'options' => [
            // 'class' => 'alert-info no-margins',
            'style' => '
                position: fixed;
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
                width: 30%;
                left: 40%;
                top: 30%;
                z-index: 99999999;
                height: 83px;
                text-align: center;
                line-height:62px;
                ',
        ],
        'body' => Yii::$app->getSession()->getFlash('info'),
    ]);
       echo '<script type="text/javascript"  ?>
            function closeSuccess(){
                $(".alert").hide();
            }    
            t = setTimeout(closeSuccess,2000);
    </script>';
}
?>