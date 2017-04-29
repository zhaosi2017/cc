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
            'unactive_call_uid',
            'call_by_same_times:datetime',
            'type',
            'contact_number',
            'status',
            'call_time:datetime',
        ],
    ]) ?>

</div>
