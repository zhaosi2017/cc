<?php

use yii\helpers\Url;
$languages = Yii::$app->params['languages'];
if(!Yii::$app->user->isGuest){
    $identity = Yii::$app->user->identity;
    $identity = (Object) $identity;
    $username = !empty($identity->account)? $identity->account : '';
    if(empty($username)){
        $username = !empty($identity->username)? $identity->username : '';
    }
    if(empty($username)){
        $username = !empty($identity->phone_number)? substr($identity->phone_number,0,2).'***'.substr($identity->phone_number,-2)  : '';
    }
    $module = $this->context->module->id;

    $localLanguage = $identity->language;
}else{
    $username = Yii::t('app/index','Guest');
    $localLanguage = isset(Yii::$app->session['language'])? Yii::$app->session['language']:'zh-CN';
}



?>
<?php $this->beginContent('@app/views/layouts/global.php'); ?>
<?= $this->render('common') ?>
<?php $srcDataPrefix = 'data:image/jpg;base64,'; ?>
<?php $imgUrl = Url::home(true) .'img/'; ?>

<style>
    .header-div-1{
        height: 100px;
        max-height: 100px;
    }
    .header-a-1{
        height: 48px;
        padding:14px 0px 14px 7px;
    }
    .header-a-1 {
        border-radius: 4px;
        line-height: 22px;
    }

    .header-span-1{
        height: 22px;
        font-size: 18px;
        font-weight: 500;
        padding: 14px 20px 14px 25px;

    }


    .header-a-1:hover,.header-a-0:hover{
        /*padding: 4px 10px;*/
        background-color: rgb(73,93,107);
    }

    .header-a-0{
        width: 48px;
        height: 22px;
        font-size: 18px;
        font-weight: 500;
        padding: 9px 20px 13px 25px;
        border-radius: 4px;
    }
</style>



<div>
    <div  class="text-right" style="background-color: rgb(96,96,96);height: 40px;line-height: 40px;">
        <?php if (!Yii::$app->user->isGuest){?>
            <div style="display: inline-block;color:white;"><?= Yii::t('app/index','Hello')?>,<?php echo $username;?></div>
        <div style="display: inline-block;color:white;"><a class="index-button-1" data-method="post" href="<?= Url::to(['/home/login/logout']) ?>"><span style="color: white;"><?= Yii::t('app/index','Logout')?></span></a> &nbsp;&nbsp;&nbsp;
            <?php echo Yii::t('app/index','Please select language');?>
        </div>
            <div style="display: inline-block;">

                <select name="language" id="language-select" onchange="ChangeLanguage()">
                    <?php foreach ($languages as $key=>$language){?>
                        <option <?php if ($localLanguage == $key){echo 'selected';}?> value="<?= $key;?>">
                            <?php echo $language;?>
                        </option>
                    <?php }?>
                </select>

            </div>
        <?php }else{?>
            <div style="display: inline-block;color:white;"><a class="index-button-1" data-method="post" href="<?= Url::to(['/home/login/login']) ?>"><span style="color: white;"><?= Yii::t('app/index','Sign in')?></span></a> &nbsp;
                <a class="index-button-1" href="/home/register/register" style="color: white;"><?= Yii::t('app/index','Sign up')?></a> &nbsp;&nbsp;&nbsp;&nbsp; </div>
            <div style="display: inline-block;color:white; ">
                <?php echo Yii::t('app/index','Please select language');?>
            </div>
            <div style="display: inline-block">
                <select name="language" id="language-session-select" onchange="ChangeLanguageSession()">
                    <?php foreach ($languages as $key=>$language){?>
                        <option <?php if ($localLanguage == $key){echo 'selected';}?> value="<?= $key;?>">
                            <?php echo $language;?>
                        </option>
                    <?php }?>
                </select>
            </div>
        <?php }?>

        <div style="display: inline-block;width: 60px;"></div>
    </div>
</div>
<!-------------------------------------------     导航栏   ------------------------------>


<div
        style="
        position: relative;
       z-index: 99999999;
        min-width: 1220px !important;
         height: 100px;line-height: 100px;
            background-color: rgb(221,231,241);
         opacity: 0.7;
    filter: alpha(opacity=70);
        "
>
    <div class="row" style="margin: auto;">



        <div style="height: 105px;    ">
            <div style="display: inline-block;    position: relative;top: -8px;">
                <div style="display: inline-block;margin-left: 67px;"><img src="/img/logo1.png" width="52" alt=""></div>
            </div>



            <div style="display: inline-block;margin-left: 20px;">
                <div style="color:black;height: 5px;font-size: 32px;font-weight: bolder;"><?= Yii::t('app/index','Call support center')?></div>
            </div>

            <div style="display: inline-block;float: right;">

                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1 header-a-0" href="<?= Url::to(['/home/default/welcome']) ?>">
                        <i class="fa fa-home"></i>&nbsp;&nbsp;
                        <span class="" style="color: black;"><?= Yii::t('app/index','Home')?></span>
                    </a>
                </div>
                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1" href="<?= Url::to(['/home/user/index']) ?>">
                        <span class="header-span-1" style="color: black;"><?= Yii::t('app/index','Account center')?></span>
                    </a>
                </div>
                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1" href="<?= Url::to(['/home/call-record/index']) ?>">
                        <span class="header-span-1" style="color: black;"><?= Yii::t('app/index','Personal call records')?></span>
                    </a>
                </div>
                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1" href="<?= Url::to(['/home/user/harassment']) ?>">
                        <span  class="header-span-1" style="color: black;"><?= Yii::t('app/index','Anti harassment')?></span>
                    </a>
                </div>
                <div style="display: inline-block;width: 50px;"></div>

            </div>

        </div>

    </div>

</div>



<!-----------------------------------------轮播---------------------------------------------->
<div style="margin-top: -150px;min-width: 900px !important;">

    <div id="myCarousel" class="carousel slide" style="">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active">
                <img src="/img/shouye1.jpg" />
                <div style="margin: auto;">
                    <div  class="shouye-img-pos"  >
                        <div class="first"><?= Yii::t('app/index','Security')?></div>
                        <div class=" second"> <?= Yii::t('app/index','Private data encryption transmission, user privacy security')?></div>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="/img/shouye2.jpg">
                <div class="shouye-img-pos">
                    <div class="first" style="font-size: 66px;"><?= Yii::t('app/index','Efficient')?></div>
                    <div class=" second"> <?= Yii::t('app/index','Make the connection more efficient')?> .
                </div>
            </div>
            </div>
            <div class="item">
                <img src="/img/shouye3.jpg">
                <div class="shouye-img-pos">
                    <div class="first"><?= Yii::t('app/index','Convenient')?></div>
                    <div class=" second"><?= Yii::t('app/index','So that communication is more convenient')?>.</div>
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

<?= $this->render('footer') ?>

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
        left: 33%;
        /*border: 1px solid;*/
        padding: 22px;
        /*background-color: rgb(247,247,247);*/
        /*opacity: 0.8;*/
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


<script>
    function ChangeLanguage()
    {
        language =  $('#language-select').val();
        if (language == '')
        {
            return false;
        }
        data = {};
        data.language = language
        $.post('/home/user/change-language',data).done(function (r) {
//            console.log(r);
            location.reload()
        })
    }
</script>


<script>
    function ChangeLanguageSession() {
        sessionLanguage = $('#language-session-select').val();
        if (sessionLanguage == '')
        {
            return false;
        }
        data = {};
        data.language = sessionLanguage
        $.post('/home/login/change-language',data).done(function (r) {
//            console.log(r);
            location.reload()
        })
    }
</script>