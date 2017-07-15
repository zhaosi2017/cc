<?php
/**
 * Created by PhpStorm.
 * User: pengzhang
 * Date: 2017/6/29
 * Time: 下午8:50
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$message = Yii::$app->getSession()->hasFlash('step-message')?  Yii::$app->getSession()->getFlash('step-message'):'';
?>


<div style="position: fixed;width: 100%;bottom: 0px;border-top: 1px solid rgb(245,245,245);background-color: white;">

    <div class="text-center" style="color: black;line-height: 30px;height: 30px;">
        <?= Yii::t('app/index','Address')?> : <?= Yii::t('app/index','Iceland')?> | &copy callu 2011-2017 | <?=Yii::t('app/index','Technical Support')?> : <?=Yii::t('app/index','Contact us')?> ( calluonline@gmail.com )</div>

</div>


<?php if(isset($message) && $message){?>
    <?= $this->render('_learn',['message'=>$message]) ?>
<?php }?>

