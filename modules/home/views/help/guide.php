<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app/nav','Use boot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Help Center'), 'url' => ['guide']];
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="/twitter/prettify.css" rel="stylesheet">
<script src="/js/home/jquery.js"></script>
<!--<script src="/twitter/jquery.bootstrap.wizard.min.js"></script>-->
<!--<script src="/twitter/prettify.js"></script>-->
<style>
    .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{
        background-color: rgb(56,181,231);
        border-left:0;
    }
    .nav > li.active {
        border-left: 0 ;
        background: rgb(56,181,231);
        border-radius: 4px;
    }
    .progress-bar{
        background-color: rgb(56,181,231);
    }
    .guide-img-1{
        width: 100%;
    }
</style>

<div class='container'>

    <section id="wizard">

        <div id="rootwizard">
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <ul class="nav nav-pills">
                            <li><a href="#tab1" data-toggle="tab">First</a></li>
                            <li><a href="#tab2" data-toggle="tab">Second</a></li>
                            <li><a href="#tab3" data-toggle="tab">Third</a></li>
                            <li><a href="#tab4" data-toggle="tab">Fourth</a></li>
                            <li><a href="#tab5" data-toggle="tab">Fifth</a></li>
                            <li><a href="#tab6" data-toggle="tab">Sixth</a></li>
                            <li><a href="#tab7" data-toggle="tab">Seventh</a></li>
                            <li><a href="#tab8" data-toggle="tab">Eight</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="bar" class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
            <div>
                <ul class="pager wizard">
                    <li class="previous first" style="display:none;"><a href="#">First</a></li>
                    <li class="previous"><a href="#">Previous</a></li>
                    <li class="next last" style="display:none;"><a href="#">Last</a></li>
                    <li class="next"><a href="#">Next</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane" id="tab1">
                    <img class="guide-img-1" src="/img/Guide/guide1.png" alt="">
                </div>
                <div class="tab-pane" id="tab2">
                    <img class="guide-img-1" src="/img/Guide/guide2.png" alt="">
                </div>
                <div class="tab-pane" id="tab3">
                    <img class="guide-img-1" src="/img/Guide/guide3.png" alt="">
                </div>
                <div class="tab-pane" id="tab4">
                    <img class="guide-img-1" src="/img/Guide/guide4.png" alt="">
                </div>
                <div class="tab-pane" id="tab5">
                    <img class="guide-img-1" src="/img/Guide/guide5.png" alt="">
                </div>
                <div class="tab-pane" id="tab6">
                    <img class="guide-img-1" src="/img/Guide/guide6.png" alt="">
                </div>
                <div class="tab-pane" id="tab7">
                    <img class="guide-img-1" src="/img/Guide/guide7.png" alt="">
                </div>
                <div class="tab-pane" id="tab8">
                    <img class="guide-img-1" src="/img/Guide/guide8.png" alt="">
                </div>

            </div>
        </div>

    </section>
</div>



<script>
//    jQuery()
    $(document).ready(function() {
        $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index+1;
            var $percent = ($current/$total) * 100;
            $('#rootwizard .progress-bar').css({width:$percent+'%'});
        }});
        window.prettyPrint && prettyPrint()
    });
</script>
