<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/6/24
 * Time: 下午2:10
 */


?>

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