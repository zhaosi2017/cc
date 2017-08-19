
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\modules\home\models\FinalChangeSearch;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\home\models\CallRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =   Yii::t('app/account/index','account change');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','account center'), 'url' => ['recharge']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;


?>
<style>
    #content-main{
        overflow-y: scroll !important;
    }
    .pagination>.active>a{
        z-index: 0;
    }
</style>
<div class="call-record-index">
    <div class="help-block m-t"></div>
    <?php  echo $this->render('_search', ['model' => $searchModel,'param'=>$param]); ?>
    <div class="help-block m-t"></div>
    <table class="table table-striped table-bordered">
    <thead>
    <tr><th><?= Yii::t('app/account/index','Id')?></th><th><?= Yii::t('app/account/index','Type')?></th> <th><?= Yii::t('app/account/index','Amount')?></th><th><?= Yii::t('app/account/index','Before trading')?></th><th><?= Yii::t('app/account/index','After trading')?></th><th><?= Yii::t('app/account/index','Trading time')?></th></tr>
    </thead>
    <tbody>
    <?php foreach ($model as $key => $val) {?>
        <tr><td><?php echo $val->id; ?> </td> <td><?php $finaChangeType = FinalChangeSearch::getFinalChangeType();if(isset($finaChangeType[$val->change_type])){ echo $finaChangeType[$val->change_type];}else{echo '';};?></td> <td><?php echo $val->amount;?></td><td><?php echo $val->before;?></td><td><?php echo $val->after;?></td><td><?= date('Y-m-d H:i:s',$val->time)?></td></tr>
    <?php }?>
    </tbody>

    </table>
    <?= LinkPager::widget(['pagination' => $pagination]); ?>
</div>
