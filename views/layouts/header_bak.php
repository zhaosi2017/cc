<?php

use yii\helpers\Url;

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

$languages = Yii::$app->params['languages'];
$localLanguage = $identity->language;
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
    .header-div-1{
        height: 100px;
        max-height: 100px;
    }
    .header-a-1{
        height: 48px;
        padding:14px 0px 14px 7px;
    }
    .header-a-1 {

        line-height: 22px;
        border-radius: 4px;
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
    .header-a-1.active{
        background-color: rgb(73,93,107);
    }

    .second-nav-div-1{
        min-width: 650px;
    }

    .second-nav-div-2{
        min-width: 450px;
    }

</style>
<div>
    <div  class="text-right" style="background-color: rgb(96,96,96);height: 40px;line-height: 40px;">
        <div style="display: inline-block;color:white;"><?= Yii::t('app/index','Hello')?>,<?php echo $username;?></div>
        <div style="display: inline-block;color:white;"><a class="index-button-1" data-method="post" href="<?= Url::to(['/home/login/logout']) ?>"><span style="color: white;"><?= Yii::t('app/index','Logout')?></span></a> &nbsp;&nbsp;&nbsp; </div>
        <div style="display: inline-block;color:white; ">
            <?= Yii::t('app/index','Please select language')?>
        </div>
        <div style="display: inline-block;">
            <?php if (!Yii::$app->user->isGuest) {?>
                <select name="language" id="language-select" onchange="ChangeLanguage()">
                    <?php foreach ($languages as $key=>$language){?>
                        <option <?php if ($localLanguage == $key){echo 'selected';}?> value="<?= $key;?>">
                            <?php echo $language;?>
                        </option>
                    <?php }?>
                </select>
            <?php }?>
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


        <div style="background-color: white;height: 105px;">
            <div style="display: inline-block;    position: relative;
    top: -8px;">
                <div style="display: inline-block;margin-left: 67px;"><img src="/img/logo1.png" width="52" alt=""></div>
            </div>



            <div style="display: inline-block;margin-left: 20px;">
                <div style="color:black;height: 5px;font-size: 32px;font-weight: bolder;"><?= Yii::t('app/index','Call support center')?></div>
            </div>

            <div style="display: inline-block;float: right;">

                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1 header-a-0 <?php if(Yii::$app->controller->action->id=='welcome'){echo 'active';}?>" href="<?= Url::to(['/home/default/welcome']) ?>">
                        <i class="fa fa-home"></i>&nbsp;&nbsp;
                        <span class="" style="color: black;"><?= Yii::t('app/index','Home')?></span>
                    </a>
                </div>
                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1 <?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id != 'harassment')|| Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram' ){ echo 'active';}?>" href="<?= Url::to(['/home/user/index']) ?>">
                        <span class="header-span-1" style="color: black;"><?= Yii::t('app/index','Account center')?></span>
                    </a>
                </div>
                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1 <?php if(Yii::$app->controller->id == 'call-record'){ echo 'active';}?>" href="<?= Url::to(['/home/call-record/index']) ?>">
                        <span class="header-span-1" style="color: black;"><?= Yii::t('app/index','Personal call records')?></span>
                    </a>
                </div>
                <div class="header-div-1" style="display: inline-block;">
                    <a class="header-a-1 <?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='harassment') ||
                        Yii::$app->controller->id == 'white-list' || Yii::$app->controller->id == 'black-list'
                    ){ echo 'active';}?>" href="<?= Url::to(['/home/user/harassment']) ?>">
                        <span  class="header-span-1" style="color: black;"><?= Yii::t('app/index','Anti harassment')?></span>
                    </a>
                </div>
                <div style="display: inline-block;width: 50px;"></div>

            </div>

        </div>

    </div>

</div>

<!-------------------------------------------    二级菜单 -个人中心  ------------------------------>

<div class="second-nav-div-1" style="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id !='harassment') ||
    Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram'
){ echo 'display:;';}else{echo 'display:none;';}?>">
    <ul style="
    /*position: relative;top: 17px; */
       padding: 10px;
    background-color: rgb(245,245,246);">
        <li  class="second-nav-li <?php if((Yii::$app->controller->id == 'user' && ( !in_array( Yii::$app->controller->action->id,['harassment','app-bind','set-phone-number','add-urgent-contact-person','links'] )))
        ){ echo 'active';}?>" style="display: inline-block;margin-left: 50px;"><a class="second-nav-a" href="/home/user/index"><?= Yii::t('app/index','Basic information')?></a></li>

        <li class="second-nav-li <?php if( (Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='app-bind') || (Yii::$app->controller->id == 'potato' || Yii::$app->controller->id == 'telegram')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/user/app-bind"><?= Yii::t('app/index','Communication app binding')?></a></li>

        <li class="second-nav-li <?php if((Yii::$app->controller->action->id == 'set-phone-number' || Yii::$app->controller->action->id == 'links' || Yii::$app->controller->action->id == 'add-urgent-contact-person')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/user/links"><?= Yii::t('app/user/links','My contact information')?></a></li>
    </ul>
</div>



<!-------------------------------------------    二级菜单 -防骚扰  ------------------------------>

<div class="second-nav-div-2" style="<?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='harassment') ||
    Yii::$app->controller->id == 'white-list' || Yii::$app->controller->id == 'black-list'
){ echo 'display:;';}else{echo 'display:none;';}?>">
    <ul style="
    /*position: relative;top: 17px; */
    padding: 10px;
    background-color: rgb(245,245,246);">
        <li  class="second-nav-li <?php if((Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id =='harassment')
        ){ echo 'active';}?>" style="display: inline-block;margin-left: 50px;"><a class="second-nav-a" href="/home/user/harassment"><?= Yii::t('app/index','Param settings')?></a></li>

        <li class="second-nav-li <?php if((Yii::$app->controller->id == 'white-list')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/white-list/index"><?= Yii::t('app/index','Whitelist')?></a></li>

        <li class="second-nav-li <?php if((Yii::$app->controller->id == 'black-list')
        ){ echo 'active';}?>" style="display: inline-block;"><a class="second-nav-a" href="/home/black-list/index"><?= Yii::t('app/index','Blacklist')?></a></li>
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
            location.reload()
        })
    }
</script>