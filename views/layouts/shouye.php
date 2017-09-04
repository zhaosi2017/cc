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
<link rel="stylesheet" type="text/css" href="/css/global/htmleaf-demo.css">
<link rel="stylesheet" type="text/css" href="/css/global/bootsnav.css">
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


    .navbar-brand{
        padding: 29px 15px;
        height: auto;
    }
    nav.navbar.bootsnav{
        border: none;
        margin-bottom: 0px;
    }
    .navbar-nav{
        float: right;
    }
    nav.navbar.bootsnav ul.nav > li > a{
        color: #474747;
        text-transform: uppercase;
        padding: 30px;
        font-size: 18px;
    }
    nav.navbar.bootsnav ul.nav > li:hover{
        background: #f4f4f4;
    }
    .nav > li:after{
        content: "";
        width: 0;
        height: 5px;
        /*background: #34c9dd;*/
        position: absolute;
        bottom: 0;
        left: 0;
        transition: all 0.5s ease 0s;
    }
    .nav > li:hover:after{
        width: 100%;
    }
    nav.navbar.bootsnav ul.nav > li.dropdown > a.dropdown-toggle:after{
        content: "+";
        font-family: 'FontAwesome';
        font-size: 16px;
        font-weight: 500;
        position: absolute;
        top: 35%;
        right: 10%;
        transition: all 0.4s ease 0s;
    }
    nav.navbar.bootsnav ul.nav > li.dropdown.on > a.dropdown-toggle:after{
        content: "\f105";
        transform: rotate(90deg);
    }
    .dropdown-menu.multi-dropdown{
        position: absolute;
        left: -100% !important;
    }
    nav.navbar.bootsnav li.dropdown ul.dropdown-menu{
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        border: none;
    }
    @media only screen and (max-width:993px){
        nav.navbar.bootsnav ul.nav > li.dropdown > a.dropdown-toggle:after,
        nav.navbar.bootsnav ul.nav > li.dropdown.on > a.dropdown-toggle:after{ content: " "; }
        .dropdown-menu.multi-dropdown{ left: 0 !important; }
        nav.navbar.bootsnav ul.nav > li:hover{ background: transparent; }
        nav.navbar.bootsnav ul.nav > li > a{ margin: 0; }
        .header-div-9{display: none !important;}
    }
    .header-div-9{

    }
    #language-select{
        font: inherit;
        color: #5d4d4d;
    }

    .carousel-inner img{
        height: auto;
    }
    .fa.fa-bars{
        background-color: gainsboro;
    }

</style>



<div>
    <div  class="text-right" style="position: relative;
    z-index: 1;background-color: rgb(96,96,96);height: 40px;line-height: 40px;">
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

        <div style="display: inline-block;width: 20px;"></div>
    </div>
</div>
<!-------------------------------------------     导航栏   ------------------------------>



<div class="htmleaf-container">

    <div class="demo" style="">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-default navbar-mobile bootsnav on">
                        <div class="navbar-header" style="width: 30%;">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                                <i class="fa fa-bars"></i>
                            </button>
                            <a style="color: #474747;" href="/"><div  style="display:inline-block;padding-left:10px;width: 52px;" class="header-div-9"><img src="/img/logo1.png" style="width: 52px;" alt=""></div></a>
                            <a style="color: #474747;" href="/"><div  style="color:#474747;display:inline-block;padding-left:10px;line-height: 78px;font-size: 18px !important;" class="header-div-9"><?= Yii::t('app/index','Call support center')?></div></a>
                        </div>
                        <div class="collapse navbar-collapse" id="navbar-menu">

                            <ul class="nav navbar-nav" data-in="fadeInDown" data-out="fadeOutUp">
                                <li class="dropdown <?php if(Yii::$app->controller->id == 'user' || in_array(Yii::$app->controller->id ,['telegram','potato','call-record','white-list','black-list'])){echo 'nav-li-active';}?>
">
                                    <a href="/home/user/index" class="dropdown-toggle" data-toggle="dropdown"><?= Yii::t('app/nav','User center')?></a>
                                    <ul class="dropdown-menu animated">

                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= Yii::t('app/nav','Account security')?></a>
                                            <ul class="dropdown-menu animated">
                                                <li><a href="/home/user/bind-email"><?= Yii::t('app/nav','Bind email')?></a></li>
                                                <li><a href="/home/user/set-phone-number"><?= Yii::t('app/nav','Bind phone')?></a></li>
                                                <li><a href="/home/user/bind-username"><?= Yii::t('app/nav','Bind username')?></a></li>
                                                <li><a href="/home/user/password"><?= Yii::t('app/nav','Updte password')?></a></li>
                                                <li><a href="/home/user/set-nickname"><?= Yii::t('app/nav','Bind nickname')?></a></li>

                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= Yii::t('app/nav','Manage center')?></a>
                                            <ul class="dropdown-menu animated">
                                                <li><a href="/home/call-record/index"><?= Yii::t('app/nav','Call record')?></a></li>
                                                <li><a href="/home/user/app-bind"><?= Yii::t('app/nav','Communication tool bind')?></a></li>
<!--                                                <li><a href="#">--><?//= Yii::t('app/nav','Personal call mode setting')?><!--</a></li>-->
                                                <li><a href="/home/user/links"><?= Yii::t('app/nav','My contact information')?></a></li>
                                                <li class="dropdown">
                                                    <a href="/home/user/harassment" class="dropdown-toggle" data-toggle="dropdown"><?= Yii::t('app/nav','Anti harassment')?></a>
                                                    <ul class="dropdown-menu animated">
                                                        <li><a href="/home/user/harassment"><?= Yii::t('app/nav','Parameter settings')?></a></li>
                                                        <li><a href="/home/white-list/index"><?= Yii::t('app/nav','Whitelist')?></a></li>

                                                        <li><a href="/home/black-list/index"><?= Yii::t('app/nav','Blacklist')?></a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>

                                    </ul>
                                </li>
<!--                                <li class="dropdown">-->
<!--                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">--><?//= Yii::t('app/nav','Account center')?><!--</a>-->
<!--                                    <ul class="dropdown-menu animated">-->
<!--                                        <li><a href="#">--><?//= Yii::t('app/nav','Recharge record')?><!--</a></li>-->
<!--                                        <li><a href="#">--><?//= Yii::t('app/nav','Charge information')?><!--</a></li>-->
<!--                                        <li><a href="#">--><?//= Yii::t('app/nav','Balance display')?><!--</a></li>-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                                <li class="dropdown">-->
<!--                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">--><?//= Yii::t('app/nav','Number store')?><!--</a>-->
<!--                                    <ul class="dropdown-menu animated">-->
<!--                                        <li><a href="#">--><?//= Yii::t('app/nav','Callu number supermarket')?><!--</a></li>-->
<!--                                    </ul>-->
<!--                                </li>-->

                                <li class="dropdown <?php if(Yii::$app->controller->id == 'help'){echo 'nav-li-active';}?>">
                                    <a href="/home/help/guide" class="dropdown-toggle" data-toggle="dropdown"><?= Yii::t('app/nav','Help Center')?></a>
                                    <ul class="dropdown-menu animated">
                                        <li><a href="/home/help/guide"><?= Yii::t('app/nav','Use boot')?></a></li>
                                        <li><a href="/home/help/software"><?= Yii::t('app/nav','Introduction to the software')?></a></li>
<!--                                        <li><a href="/home/help/qustion-answer">--><?//= Yii::t('app/nav','Q&A')?><!--</a></li>-->
<!--                                        <li><a href="/home/help/online-service">--><?//= Yii::t('app/nav','Online service')?><!--</a></li>-->
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-----------------------------------------轮播---------------------------------------------->
<div style="margin-top: 0px;min-width: 900px !important;height: auto;">

    <div id="myCarousel" class="carousel slide" style="">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
<!--            <li data-target="#myCarousel" data-slide-to="2"></li>-->
        </ol>
        <div class="carousel-inner">


            <div class="item active">
                <img src="/img/shouye3.jpg">
                <div class="shouye-img-pos  shouye-img-div">
                    <div class="text-left" style="font-size: 50px";><?= Yii::t('app/index','Product description')?></div>
                    <div class=" text-left shouye-div-2"><?= Yii::t('app/index','The most secure network voice call platform')?>.</div>
                    <div class=" text-left shouye-div-2"><?= Yii::t('app/index','Provide intelligent voice call reminder service for emergency matters')?>.</div>
                    <div class=" text-left shouye-div-2"><?= Yii::t('app/index','In your communication app directly call the other phone, call each other in time to reply to you')?>.</div>
                    <div class=" text-left shouye-div-2"><?= Yii::t('app/index','Make communication more efficient and convenient')?>.</div>
                </div>
            </div>

            <div class="item ">
                <img src="/img/shouye2.jpg">
                <div class="shouye-img-pos shouye-img-div">
                    <div class="text-left shouye-div-2" style="font-size: 50px;"><?= Yii::t('app/index','Operating procedures')?></div>
                    <div class="text-left shouye-div-2"> <?= Yii::t('app/index', '1. Use the mailbox or mobile phone number to register the account, and landing.')?></div>
                    <div class="text-left shouye-div-2"> <?= Yii::t('app/index', '2. Enter the account center to edit the basic information, bind the communication app, add contact information.')?></div>
                    <div class="text-left shouye-div-2"> <?= Yii::t('app/index', '3. Enter the anti-harassment page to set the anti-harassment parameters.')?></div>
                    <div class="text-left shouye-div-2 "> <?= Yii::t('app/index', '4. Open the communication app, share the other business card to the robot, make a call, the other party can receive incoming calls. But also by sharing the other business card to the robot, add each other to the white list, blacklist, so you from harassment.')?></div>
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
        margin:0 0 0 0;
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
        left: 23%;
        /*border: 1px solid;*/
        padding: 22px;
        /*background-color: rgb(247,247,247);*/
        /*opacity: 0.8;*/
        animation: myfirst 2s;
        -webkit-animation: myfirst 2s;
    }
    .shouye-img-pos .first{
        font-size: 50px;
    }
    .shouye-img-pos .second{
        font-size: 18px;
        font-weight: bold;
    }

    .shouye-img-div{
        left: 12%;
        width: 65%;
    }


    .shouye-div-2{
        font-size: 18px;
        width: 58%;
    }

    @keyframes myfirst
    {
        0%   {opacity: 0;}
        /*25%  {opacity: 0.25;}*/
        50%  {opacity: 0.50;}
        100% {opacity: 1;}
    }

    @-webkit-keyframes myfirst /* Safari 与 Chrome */
    {
        0%   {opacity: 0;}
        /*25%  {opacity: 0.25;}*/
        50%  {opacity: 0.50;}
        100% {opacity: 1;}
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
<script type="text/javascript" src="/js/global/bootstrap.min.js"></script>

<script type="text/javascript" src="/js/global/bootsnav.js"></script>