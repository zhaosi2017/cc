<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app/nav','Online service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Help Center'), 'url' => ['guide']];
$this->params['breadcrumbs'][] = $this->title;
?>