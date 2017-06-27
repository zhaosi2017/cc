<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '账户中心';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">
    <div class="row">
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-content text-center p-md" style="height: 553px;">
                    <h4 class="m-b-lg">个人中心</h4>
                    <div class="text-left">
                        <p class="m-t-lg">管理个人昵称、联系电话。</p>
                        <p class="m-t-lg" >邮箱：<?php echo $model->account; ?>  <a   class="btn btn-primary btn-sm pull-right" href="<?php echo Url::to(['/home/user/bind-email'])?>"><?php echo $model->username?'修改':'去添加';?></a>    </p>
                        <p class="m-t-lg" >用户名：<?php echo $model->username; ?>    <a   class="btn btn-primary btn-sm pull-right" href="<?php echo Url::to(['/home/user/bind-username'])?>"><?php echo $model->username?'修改':'去添加';?></a></p>

                        <p class="m-t-lg">昵称：<?php echo $model->nickname; ?><a href="<?php echo Url::to(['/home/user/set-nickname']) ?>" class="btn btn-primary btn-sm pull-right"><?php echo $model->nickname ? '修改' : '去设置'?></a></p>
                        <p class="m-t-lg">白名单开关：<?php echo $model->whitelist_switch ? '开':'关' ;?></p>

<!--                        <p class="m-t-lg">防骚扰：-->
<!--                         <span title="--><?php //echo $model->un_call_number.('(总数)  ');echo $model->un_call_by_same_number.('(同一人)  ');echo $model->long_time.('(时间)');?><!--"  alt="--><?php //echo $model->un_call_number.('(总数)  ');echo $model->un_call_by_same_number.('(同一人)  ');echo $model->long_time.('(时间)');?><!--" style="    overflow: hidden;-->
<!--    text-overflow: hidden;-->
<!--    white-space: nowrap;-->
<!--    word-break: keep-all;-->
<!--    width: 160px;-->
<!--    max-width: 160px;-->
<!--    display: inline-block; " >--><?php //echo $model->un_call_number.('(总数)  ');echo $model->un_call_by_same_number.('(同一人)  ');echo $model->long_time.('  (时间)');?><!--</span>-->
<!--                         <a href="--><?php //echo Url::to(['/home/user/harassment']) ?><!--" class="btn btn-primary btn-sm pull-right">--><?php //echo $model->un_call_number ? '修改' : '去设置'?>
<!--                             -->
<!--                         </a>-->
<!--                         </p>-->

                        <p class="m-t-lg" style="margin-top: 43px;">登录密码：******<a href="<?php echo Url::to(['/home/user/password']) ?>" class="btn btn-primary btn-sm pull-right">去修改</a></p>



                        </p>
                        <div class="m-t-lg" style="text-right">联系电话：
                                <div style="float: right ;    height: 120px;overflow: scroll;overflow-x: visible;">
                                    <table>
                                        <?php   foreach($user_phone_numbers as $key=>$number){?>
                                            <tr> <td><?php echo   '+'.$number->phone_country_code . $number->user_phone_number  ;?> &nbsp;&nbsp;</td>
                                                 <td><div class="pull-right btn-group">
                                                        <a href="<?php echo Url::to(['/home/user/set-phone-number' ,'phone_number'=>$number->user_phone_number]) ?>" class="btn btn-primary btn-sm">修改</a>
                                                        <a href="<?php echo Url::to(['/home/user/delete-number','id'=>$model->id, 'type'=>'phone_number', 'phone_number'=>$number->user_phone_number , 'country_code'=>$number->phone_country_code]) ?>" data-method="post" data-confirm="你确定要删除吗?" class="btn btn-danger btn-sm">删除</a>
                                                    </div>
                                                 </td>
                                            </tr>
                                        <?php  } ?>
                                        <tr> <td></td>
                                            <td><a href="<?php echo Url::to(['/home/user/set-phone-number']) ?>" class="btn btn-primary btn-sm pull-right">去绑定</a><td>
                                        </tr>
                                    </table>
                                </div>
                        </div>

<!--                        <div class="text-right" style="    position: relative; top:105px; left:180px">-->
<!--                            <a class="btn btn-primary m-t-md" href="--><?php //echo Url::to(['/home/user/password'])?><!--">修改密码</a>-->
<!--                        </div>-->
<!--                        -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-content text-center p-md" style="height: 553px;">
                        <h4 class="m-b-lg">账号绑定</h4>
                        <div class="text-left">
                            <p class="m-t-lg">绑定telegram或potato，正式启用离线呼叫提醒功能，让人可以找到您，同时也能让您找到别人！</p>
                            <div class="m-t-lg">Potato：<?php echo $model->potato_number ? '+'.$model->potato_number : '无'; ?>
                                <?php if(!$model->potato_number){ ?>
                                <a href="<?php echo Url::to(['/home/potato/bind-potato']) ?>" class="btn btn-primary btn-sm pull-right">立即绑定</a>
                                <?php }else{ ?>
                                    <div class="pull-right btn-group">
                                        <a href="<?php echo Url::to(['/home/potato/bind-potato']) ?>" class="btn btn-primary btn-sm">修改</a>
                                        <a href="<?php echo Url::to(['/home/potato/unbundle-potato','id'=>$model->id, 'type'=>'potato_number']) ?>" data-method="post" data-confirm="你确定要解除绑定吗?" class="btn btn-danger btn-sm">解除绑定</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="m-t-lg">Telegram：<?php echo $model->telegram_number ? '+' .$model->telegram_number : '无'; ?>
                                <?php if(!$model->telegram_number){ ?>
                                <a href="<?php echo Url::to(['/home/telegram/bind-telegram']) ?>" class="btn btn-primary btn-sm pull-right">立即绑定</a>
                                <?php }else{ ?>
                                    <div class="pull-right btn-group">
                                        <a href="<?php echo Url::to(['/home/telegram/bind-telegram']) ?>" class="btn btn-primary btn-sm">修改</a>
                                        <a href="<?php echo Url::to(['/home/telegram/unbundle-telegram']) ?>" data-method="post" data-confirm="你确定要解除绑定吗?" class="btn btn-danger btn-sm">解除绑定</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-content text-center p-md" style="height: 553px;">
                        <h4 class="m-b-lg">紧急联系人</h4>
                        <div class="text-left">
                            <p class="m-t-lg">为账号设置2个紧急联系人，便于自己联系电话无法使用时其他人可以联系到自己！</p>
                            <?php foreach($user_gent_contents  as $content){ ?>

                                <div class="fa-border p-sm">
                                    <p class="m-t-sm">联系人&nbsp;&nbsp;&nbsp;&nbsp;：<?php echo $content->contact_nickname; ?></p>
                                    <div class="m-t-sm">
                                        <span>联系电话：<?php echo $content->contact_country_code.' '.$content->contact_phone_number; ?></span>
                                        <div class="pull-right btn-group m-t-n-xs">
                                            <a href="<?php echo Url::to(['/home/user/add-urgent-contact-person', 'modify' => '1' , 'id'=>$content->id]) ?>" class="btn btn-primary btn-sm">修改</a>
                                            <a href="<?php echo Url::to(['/home/user/delete-urgent-contact-person', 'type'=>'1' , 'id'=>$content->id]) ?>" class="btn btn-danger btn-sm">删除</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="help-block"></div>

                            <?php } ?>
                            <div class="text-right">
                                <a class="btn btn-primary m-t-md" href="<?php echo Url::to(['/home/user/add-urgent-contact-person'])?>">立即添加</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
