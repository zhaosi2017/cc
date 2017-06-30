<?php

use yii\helpers\Url;

$identity = Yii::$app->user->identity;
$identity = (Object) $identity;

$username = !empty($identity->account)? $identity->account : '';
if(empty($username)){
    $username = !empty($identity->username)? $identity->username : '';
}
if(empty($username)){
    $username = !empty($identity->phone_number)? substr($identity->phone_number,0,1).'***'.substr($identity->phone_number,-1,1)  : '';
}
$module = $this->context->module->id;
?>
<?php $this->beginContent('@app/views/layouts/global.php'); ?>

<?php $srcDataPrefix = 'data:image/jpg;base64,'; ?>
<?php $imgUrl = Url::home(true) .'img/'; ?>

<style>
    .nav > li.active{
        border-left: 0px !important;
        border-radius: 6px !important;
    }
    body{
        background: white !important;
    }
    .pagination{
        margin: 3px 0px !important;
    }

</style>
<div>
    <div  class="text-right" style="background-color: rgb(96,96,96);height: 40px;line-height: 40px;">
        <div style="display: inline-block;color:white;">您好,<?php echo $username;?></div>
        <div style="display: inline-block;color:white;"><a data-method="post" href="<?= Url::to(['/home/login/logout']) ?>"><span style="color: white;">退出</span></a> &nbsp;&nbsp;|&nbsp;&nbsp; </div>
        <div style="display: inline-block;color:white; ">
            请选择语言 中文简体
        </div>
        <div style="display: inline-block;width: 60px;"></div>
    </div>
</div>
<!-------------------------------------------     导航栏   ------------------------------>

<div
        style="
        /*position: relative;*/
       /*z-index: 99999999;*/
        min-width: 1220px !important;
         height: 100px;line-height: 100px;
         background-color: rgb(221,231,241);
        "
>
    <div class="row" style="margin: auto;">



        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="background-color: white;">
            <div class="container-fluid">

                <div class="navbar-header" >
                    <div>
                        <div style="display: inline-block;"><img src="/img/logo1.png" width="52" alt=""></div>
                        <div style="display: inline-block;">
                            <div style="color: black;height: 5px;font-size: 32px;font-weight: bolder;">呼叫支持中心</div>
                            <div style="color:black;height: 20px;position: relative; top: 22px;">c a l l &nbsp;&nbsp;&nbsp; s u p p o r t &nbsp;&nbsp;&nbsp;    c e n t e r</div>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav navbar-right" style="margin-top: 24px;">
                        <li style="color: black;font-size: 18px;">

                            <a class="" href="<?= Url::to(['/home/default/welcome']) ?>">
                                <i class="fa fa-home"></i>
                                <span style="color: black;">首页</span>
                            </a>
                        </li>
                        <li style="color: black;font-size: 18px;" class="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id != 'harassment')|| Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram' ){ echo 'active';}?>">
                            <a class="" href="<?= Url::to(['/home/user/index']) ?>">
                                <span style="color: black;">账户中心</span>
                            </a>
                        </li>
                        <li style="color: black;font-size: 18px;" class="<?php if(Yii::$app->controller->id == 'call-record'){ echo 'active';}?>">
                            <a class="" href="<?= Url::to(['/home/call-record/index']) ?>">
                                <span style="color: black;">个人通话记录</span>
                            </a>
                        </li>

                        <li  style="color: black;font-size: 18px;" class="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='harassment') ||
                            Yii::$app->controller->id == 'white-list' || Yii::$app->controller->id == 'black-list'
                        ){ echo 'active';}?>">
                            <a class="" href="<?= Url::to(['/home/user/harassment']) ?>">
                                <span style="color: black;">防骚扰</span>
                            </a>

                        </li>




                    </ul>

                </div>
            </div>
        </nav>



    </div>

</div>

<!-------------------------------------------    二级菜单 -个人中心  ------------------------------>

<div style="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id !='harassment') ||
    Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram'
){ echo 'display:;';}else{echo 'display:none;';}?>">
    <ul style="
    /*position: relative;top: 17px; */
       padding: 10px;
    background-color: rgb(245,245,246);">
        <li  class="second-nav-li <?php if((Yii::$app->controller->id == 'user' && ( !in_array( Yii::$app->controller->action->id,['harassment','app-bind','set-phone-number','add-urgent-contact-person','links'] )))
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/user/index">基本资料</a></li>

        <li class="second-nav-li <?php if( (Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='app-bind') || (Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/user/app-bind">通讯app绑定</a></li>

        <li class="second-nav-li <?php if((Yii::$app->controller->action->id == 'set-phone-number' || Yii::$app->controller->action->id == 'links' || Yii::$app->controller->action->id == 'add-urgent-contact-person')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/user/links">联系方式</a></li>
    </ul>
</div>



<!-------------------------------------------    二级菜单 -防骚扰  ------------------------------>

<div style="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='harassment') ||
    Yii::$app->controller->id == 'white-list' || Yii::$app->controller->id == 'black-list'
){ echo 'display:;';}else{echo 'display:none;';}?>">
    <ul style="
    /*position: relative;top: 17px; */
    padding: 10px;
    background-color: rgb(245,245,246);">
        <li  class="second-nav-li <?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='harassment')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/user/harassment">防骚扰</a></li>

        <li class="second-nav-li <?php if((Yii::$app->controller->id == 'white-list')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/white-list/index">白名单</a></li>

        <li class="second-nav-li <?php if((Yii::$app->controller->id == 'black-list')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/black-list/index">黑名单</a></li>
    </ul>
</div>





        <div class="row" id="content-main" style=" height: calc(100% - 213px)">
            <?= isset($content) ? $content : '' ?>
        </div>
        <!--<div class="footer">
            <div class="text-left">
                <a href="#">V 1.0.0</a>
            </div>
        </div>-->

<?= $this->render('footer') ?>

    </div>
</div>
<?php $this->endContent(); ?>


<style>
    .second-nav-li{
        background-color: rgb(99,181,221);
        padding: 5px 15px;
        margin-left: 20px;
        border-radius: 6px;
    }
    .second-nav-li:hover ,.second-nav-li.active{
        background-color: rgb(46,83,112) !important;
    }

    .second-nav-a{
        padding: 5px 10px;
        color: white !important;
    }
</style>