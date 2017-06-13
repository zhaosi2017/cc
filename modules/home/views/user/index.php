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
                <div class="ibox-content text-center p-md">
                    <h4 class="m-b-lg">个人中心</h4>
                    <div class="text-left">
                        <p class="m-t-lg">管理个人昵称、联系电话</p>
                        <p class="m-t-lg">账号：<?php echo $model->account; ?></p>
                        <p class="m-t-lg">昵称：<?php echo $model->nickname; ?><a href="<?php echo Url::to(['/home/user/set-nickname']) ?>" class="btn btn-primary btn-sm pull-right"><?php echo $model->nickname ? '修改' : '去设置'?></a></p>
                        <div class="m-t-lg">联系电话：<?php echo $model->phone_number ? '+' . $model->country_code . $model->phone_number : '无'; ?>
                            <?php if(!$model->phone_number){ ?>
                                <a href="<?php echo Url::to(['/home/user/set-phone-number']) ?>" class="btn btn-primary btn-sm pull-right">去绑定</a>
                            <?php }else{ ?>
                                <div class="pull-right btn-group">
                                    <a href="<?php echo Url::to(['/home/user/set-phone-number']) ?>" class="btn btn-primary btn-sm">修改</a>
                                    <a href="<?php echo Url::to(['/home/user/delete-number','id'=>$model->id, 'type'=>'phone_number']) ?>" data-method="post" data-confirm="你确定要删除吗?" class="btn btn-danger btn-sm">删除</a>
                                </div>
                            <?php } ?>
                        </div>
                        <a class="btn btn-primary full-width m-t-lg" href="<?php echo Url::to(['/home/user/password'])?>">修改密码</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-content text-center p-md">
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
                    <div class="ibox-content text-center p-md">
                        <h4 class="m-b-lg">紧急联系人</h4>
                        <div class="text-left">
                            <p class="m-t-lg">为账号设置2个紧急联系人，便于自己联系电话无法使用时其他人可以联系到自己</p>
                            <?php
                                if ($model->urgent_contact_person_one) {
                            ?>
                                <div class="fa-border p-sm">
                                    <p class="m-t-sm">联系人一：<?php echo $model->urgent_contact_person_one; ?></p>
                                    <div class="m-t-sm">
                                        <span>联系电话：<?php echo $model->urgent_contact_one_country_code.' '.$model->urgent_contact_number_one; ?></span>
                                        <div class="pull-right btn-group m-t-n-xs">
                                            <a href="<?php echo Url::to(['/home/user/add-urgent-contact-person', 'modify' => '1']) ?>" class="btn btn-primary btn-sm">修改</a>
                                            <a href="<?php echo Url::to(['/home/user/delete-urgent-contact-person', 'type'=>'1']) ?>" class="btn btn-danger btn-sm">删除</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="help-block"></div>
                            <?php
                                } else {
                            ?>
                                    <div class="fa-border p-sm">
                                        <p class="m-t-sm">联系人一：无</p>
                                        <p class="m-t-sm">联系电话：无</p>
                                    </div>
                                    <div class="help-block"></div>
                            <?php
                                }
                            ?>
                            <?php
                                if ($model->urgent_contact_person_two) {
                            ?>
                                <div class="fa-border p-sm">
                                    <p class="m-t-sm">联系人二：<?php echo $model->urgent_contact_person_two; ?></p>
                                    <div class="m-t-sm">
                                        <span>联系电话：<?php echo $model->urgent_contact_two_country_code.' '.$model->urgent_contact_number_two; ?></span>
                                        <div class="pull-right btn-group m-t-n-xs">
                                            <a href="<?php echo Url::to(['/home/user/add-urgent-contact-person', 'modify'=>'2']) ?>" class="btn btn-primary btn-sm"">修改</a>
                                            <a href="<?php echo Url::to(['/home/user/delete-urgent-contact-person/', 'type'=>'2']) ?>" class="btn btn-danger btn-sm">删除</a>
                                        </div>

                                    </div>
                                </div>
                            <?php
                                } else {
                            ?>
                                <div class="fa-border p-sm">
                                    <p class="m-t-sm">联系人二：无</p>
                                    <p class="m-t-sm">联系电话：无</p>
                                </div>
                            <?php
                                }
                            ?>
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
