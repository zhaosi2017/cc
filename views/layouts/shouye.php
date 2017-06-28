<?php

use yii\helpers\Url;

$identity = Yii::$app->user->identity;
$identity = (Object) $identity;

$account_info = \app\modules\home\models\User::findOne($identity->id);
$username = isset($identity->account) ? $identity->account : 'Guest';
$module = $this->context->module->id;
?>
<?php $this->beginContent('@app/views/layouts/global.php'); ?>

<?php $srcDataPrefix = 'data:image/jpg;base64,'; ?>
<?php $imgUrl = Url::home(true) .'img/'; ?>
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
        style="position: relative;
        z-index: 99999999;min-width: 1220px !important;
         height: 100px;line-height: 100px;
         background-color: rgb(221,231,241);opacity: 0.5;
        filter: alpha(opacity=50)"
>
    <div class="row" style="margin: auto;">



        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">

                    <div class="navbar-header" >
                        <div>
                            <div style="display: inline-block;"><img src="/img/logo1.png" width="52" alt=""></div>
                            <div style="display: inline-block;">
                                <div style="height: 5px;font-size: 32px;font-weight: bolder;">呼叫支持中心</div>
                                <div style="height: 20px;position: relative; top: 22px;">c a l l &nbsp;&nbsp;&nbsp; s u p p o r t &nbsp;&nbsp;&nbsp;    c e n t e r</div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav navbar-right" style="margin-top: 24px;">
                        <li style="color: black;font-size: 18px;">
                            <a class="" href="<?= Url::to(['/home/default/welcome']) ?>">
                                <i class="fa fa-home"></i>
                                <span >首页</span>
                            </a>
                        </li>
                        <li style="color: black;font-size: 18px;" class="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id != 'harassment')|| Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram' ){ echo 'active';}?>">
                            <a class="" href="<?= Url::to(['/home/user/index']) ?>">
                                <span >账户中心</span>
                            </a>
                        </li>
                        <li style="color: black;font-size: 18px;" class="<?php if(Yii::$app->controller->id == 'call-record'){ echo 'active';}?>">
                            <a class="" href="<?= Url::to(['/home/call-record/index']) ?>">
                                <span >个人通话记录</span>
                            </a>
                        </li>

                        <li  style="color: black;font-size: 18px;" class="<?php if(Yii::$app->controller->id == 'call-record'){ echo 'active';}?>">
                            <a class="" href="<?= Url::to(['/home/user/harassment']) ?>">
                                <span >防骚扰</span>
                            </a>
                        </li>




                    </ul>

                </div>
            </div>
        </nav>



    </div>

</div>
<!-----------------------------------------轮播---------------------------------------------->
<div style="margin-top: -150px;min-width: 900px !important;">

    <div id="myCarousel" class="carousel slide">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img src="/img/shouye1.jpg" />
                <div  class="shouye-img-pos"  >
                    <div class="first">安全</div>
                   <div class=" second"> 私 密 数 据  加 密 传 输 ，用 户 隐 私 安 全 保 障</div>
                </div>
            </div>
            <div class="item">
                <img src="/img/shouye2.jpg">
                <div class="shouye-img-pos">
                    <div class="first" style="font-size: 66px;">高效</div>
                    <div class=" second"> 让 连 接 更 高 效 ， 沟 通 更 便 捷.
                </div>
            </div>
            </div>
            <div class="item">
                <img src="/img/shouye3.jpg">
                <div class="shouye-img-pos">
                    <div class="first">高效</div>
                    <div class=" second">让 连 接 更 高 效 ， 沟 通 更 便 捷.</div>
                </div>
            </div>
        </div>
        <a href="#myCarousel" data-slide="prev" class="carousel-control left">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a href="#myCarousel" data-slide="next" class="carousel-control right">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>

</div>

<!--<div class="row" id="content-main" style="overflow: auto; height: calc(100% - 50px)">-->
<!--    --><?//= isset($content) ? $content : '' ?>
<!--</div>-->

</div>
</div>
<?php $this->endContent(); ?>

<style>
    #myCarousel{
        margin:50px 0 0 0;
    }

    .nav .navbar-nav li:hover{
         background-color: rgb(221,231,241) !important;opacity: 0.5; !important;
    }
    .nav .navbar-nav li>a:hover{
        color: red;
    }

    .shouye-img-pos
    {
        position: fixed;
        top:150px;
        left: 50px;
        /*border: 1px solid;*/
        padding: 22px;
        background-color: rgb(247,247,247);
        opacity: 0.8;
    }
    .shouye-img-pos .first{
        font-size: 66px;
    }
    .shouye-img-pos .second{
        font-size: 18px;
        font-weight: bold;
    }
</style>

<script>
    $('#myCarousel').carousel({
        //自动4秒播放
        interval : 4000,
    });
</script>