<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app/nav','Q&A');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Help Center'), 'url' => ['guide']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>

    <div>
        <h3>
            1.收到不到短信？
        </h3>
        <div>
            国码需要填写你手机所在地的国码
        </div>
    </div>

    <div>
        <h3>
            2.收到不到邮件？
        </h3>
        <div>
            请确保是正确的邮箱
        </div>
    </div>
    <div>
        <h3>
            3.账号被冻结或者被锁如何解决？
        </h3>
        <div>
            可以通电话或者邮箱重新找回密码，重置密码，就可以解锁
        </div>
    </div>
</div>
