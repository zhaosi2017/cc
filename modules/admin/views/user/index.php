<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,

            'layout' => "{items}\n  <div><ul class='pagination'><li style='display:inline;'><span>共".$dataProvider->getTotalCount(). "条数据 <span></li></ul>{pager}  </div>",
            'pager'=>[
                //'options'=>['class'=>'hidden']//关闭自带分页
                
                'firstPageLabel'=>"首页",
                'prevPageLabel'=>'上一页',
                'nextPageLabel'=>'下一页',
                'lastPageLabel'=>'末页',
                'maxButtonCount' => 9,
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header'=>'序号'],

                'account:ntext',
                'nickname:ntext',
                'un_call_number',
                'un_call_by_same_number',

                ['header'=>'时间(min)', 'value'=>function($data){
                    return $data['long_time'];
                }],
                
                ['header'=>'联系电话' , 'format'=>'html' , 'value'=>function($data){
                        $phone_number = (new \app\modules\home\models\UserPhone())::findAll(array('user_id'=>$data->id));
                        if(empty($phone_number)){
                                return '';
                        }
                        return '+'.$phone_number[0]->phone_country_code . $phone_number[0]->user_phone_number;

                }],
                'telegram_number',
                'potato_number',

                ['header'=>'紧急联系人/电话', 'format'=>'html', 'value'=>function($data){
                        $contacts = (new \app\modules\home\models\UserGentContact())::findAll(array('user_id'=>$data->id));
                        if(empty($contacts)){
                            return '';
                        }
                        return $contacts[0]->contact_nickname . '<br> +' . $contacts[0]->contact_country_code . $contacts[0]->contact_phone_number;
                }],
                ['header'=>'注册IP/注册时间', 'format'=>'html', 'value'=>function($data){
                    return $data->reg_ip. '<br> ' . date('Y-m-d H:i:s' , $data->reg_time);
                }],
                ['header'=>'最后登陆IP/最后登陆时间', 'format'=>'html', 'value'=>function($data){
                    return $data->login_ip. '<br> ' . date('Y-m-d H:i:s' , $data->login_time);
                }],
            ],
        ]); ?>
    <?php Pjax::end(); ?>

</div>
