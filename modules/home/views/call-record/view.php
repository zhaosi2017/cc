<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\CallRecord */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => '呼叫记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="call-record-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'active_call_uid',
            'active_account',
            'active_nickname',
            'contact_number',
            'unactive_call_uid',
            'unactive_account',
            'unactive_nickname',
            'unactive_contact_number',
            'call_by_same_times:datetime',
            'type',
            'statusData',
            'call_time:datetime',
        ],
    ]) ?>

</div>
