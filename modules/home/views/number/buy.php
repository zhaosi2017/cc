<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

$this->title =   Yii::t('app/nav','Callu number supermarket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/nav','Number store'), 'url' => ['number/index']];
$this->params['breadcrumbs'][] = $this->title;
$actionId = Yii::$app->requestedAction->id;

?>

<div>



</div>

<div class="middle-box" style="    margin-top:0px !important;">
    <div>
        <div class="form-group">
            <label class="col-sm-4" for="number"><?= Yii::t('app/number/index','Phone number')?></label>
            <div class="col-sm-7"><input readonly="readonly" class="form-control " type="text" id="number" value="<?= $model->number ?>"></div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>
        <div class="col-sm-12"></div>

        <div class="form-group">
            <label class="col-sm-4" for="price"><?= Yii::t('app/number/index','Price')?></label>
            <div class="col-sm-7"><input readonly="readonly" class="form-control " type="text" id="price" value="<?= $model->price ?>"></div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>
        <div class="col-sm-12"></div>

        <div class="form-group">
            <label class="col-sm-4" for="comment"><?= Yii::t('app/number/index','Introduction')?></label>
            <div class="col-sm-7"><input readonly="readonly" class="form-control " type="text" id="comment" value="<?= $model->comment ?>"></div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>
        <div class="col-sm-12"></div>

        <div class="form-group">
            <label class="col-sm-4" for="status"><?= Yii::t('app/number/index','Status')?></label>
            <div class="col-sm-7">
                <input readonly="readonly"  class="form-control " type="text" id="status" value="<?php $numStatus = \app\modules\home\models\CallNumber::getNumbStatus();echo $numStatus[$model->status]; ?>">
            </div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>
        <div class="col-sm-12"></div>

        <div class="form-group">
            <label class="col-sm-4" for="status"><?= Yii::t('app/number/index','Can be used to rent the start time')?></label>
            <div class="col-sm-7">
                <div><span style="display: none;" id="begin_time_1"><?= strtotime(date('Y-m-d',$model->begin_time)) ?></span><?= date('Y-m-d',$model->begin_time) ?></div>

            </div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>
        <div class="col-sm-12"></div>
        <div class="form-group">
            <label class="col-sm-4" for="status"><?= Yii::t('app/number/index','Can be used to rent the end time')?></label>
            <div class="col-sm-7">
                    <div><span style="display: none;" id="end_time_1"><?= strtotime(date('Y-m-d',$model->end_time)) ?></span><?= date('Y-m-d',$model->end_time) ?></div>

            </div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>
        <div class="col-sm-12"></div>


          </div>
    <div style="display: none">
        <span id="morenTime"><?= strtotime(date('Y-m-d', time()))?></span>
    </div>
    <form id="buyForm" action="/home/number/sure-buy" method="post">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" name="callnumberid" value="<?= $model->id?>">
        <input type="hidden" name="number" value="<?= $model->number?>">
        <input type="hidden" name="buyid" value="<?= $id ?>">

        <div class="form-group">
            <label class="col-sm-4" for="status"><?= Yii::t('app/number/index','Rent start time')?></label>
            <div class="col-sm-7">
                <?php    echo DateTimePicker::widget([
                    'name' => 'begin_time',
                    'options' => ['placeholder' => ''],
                    //注意，该方法更新的时候你需要指定value值
                    'value' =>date('Y-m-d'),
                    'id'=>'callrecordsearch-begin_time',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd ',
                        'todayHighlight' => true,
                        'minView'=> "2",



                    ],
                    'pluginEvents' => [
                        "changeDate" => "function(e) {dd = e.date; var tt = parseInt(dd.getTime()/1000);var bt = parseInt($('#begin_time_1').html());var et = parseInt($('#end_time_1').html());
                 var endTime = $('#callrecordsearch-end_time').val();
                 
                 var morenTime = $('#morenTime').html();
                
                 if(tt < morenTime){   
                      $('#callrecordsearch-begin_time').val('');alert('".Yii::t('app/number/index','The rental start time can not be less than today')."');return false;
                   }
                 if( tt < bt || tt > et || tt < morenTime){
                    alert('".Yii::t('app/number/index','Please enter the correct time')."');$('#callrecordsearch-begin_time').val('');return false;
                 }
                   
                   if(tt && endTime  ){ 
                   var dates = new Date(endTime.replace(/-/g, '/'));time1 = dates.getTime()/1000; 
                   if(tt == time1){
                        $('#callrecordsearch-begin_time').val('');alert('".Yii::t('app/number/index','The rent start time can not be equal to the rent end time')."');return false;
                   }
                   if(tt > time1){
                        $('#callrecordsearch-begin_time').val('');alert('".Yii::t('app/number/index','The rent start time can not be greater than the rent end time')."');return false;
                   }
                   }

        
                     if(tt && endTime){
                        var _dates = new Date(endTime.replace(/-/g, '/'));
                        var _endTimes = _dates.getTime()/1000;
                        
                        
                     
                         var _day = (_endTimes - tt)/86400;
                        var price = $('#price').val();
                        var totalPrice = _day * price;
                        $('#totalPrice').val(parseFloat(totalPrice).toFixed(4));
                       
                   }

                   
                   }",
                    ],


                ]);?>
            </div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>




        <div class="form-group">
            <label class="col-sm-4" for="status"><?= Yii::t('app/number/index','Rent end time')?></label>
            <div class="col-sm-7">
                <?php    echo DateTimePicker::widget([
                    'name' => 'end_time',
                    'id'=>'callrecordsearch-end_time',
                    'options' => ['placeholder' => ''],

                    //注意，该方法更新的时候你需要指定value值
                    'value' =>  date('Y-m-d',strtotime("+1 day")),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
//                'formatDate'=>'Y/m/d'
                        'minView'=> "2",
                    ],
                    'pluginEvents' => [
                        "changeDate" => "function(e) {
                        ddd = e.date; 
                        var ttt = parseInt(ddd.getTime()/1000);
                        var btt = parseInt($('#begin_time_1').html());
                        var ett = parseInt($('#end_time_1').html());
                        var beginTime = $('#callrecordsearch-begin_time').val();
                       
                        
                      
                        var morenTime = $('#morenTime').html();
                         if( ttt < btt || ttt > ett || (beginTime && ttt < beginTime) ){ 
                            $('#callrecordsearch-end_time').val('');
                            alert('" .Yii::t('app/number/index','Please enter the correct time')."');
                           
                            return false;
                         }  
                       
                       if(ttt < morenTime){    
                         $('#callrecordsearch-end_time').val('');alert('".Yii::t('app/number/index','The rental end time can not be less than today')."');return false;
                       }
                       
                       
                   if(ttt && beginTime  ){ 
                        var datess = new Date(beginTime.replace(/-/g, '/'));
                         time2 = datess.getTime()/1000; 
                       
                       if(ttt==time2){
                            $('#callrecordsearch-end_time').val('');alert('".Yii::t('app/number/index','The rent end time can not be equal to the rent start time')."');return false;
                       }
                       
                        if(ttt < time2){
                        $('#callrecordsearch-end_time').val('');alert('".Yii::t('app/number/index','The rent end time can not be less than the rent start time')."');return false;
                        }
                   }
                   
                   if(beginTime && ttt){
                        var _datess = new Date(beginTime.replace(/-/g, '/'));
                        var beginTime = _datess.getTime()/1000;
                         var _day = (ttt - beginTime)/86400;
                        var price = $('#price').val();
                        var totalPrice = _day * price;
                        $('#totalPrice').val(parseFloat(totalPrice).toFixed(4));
                       
                   }
                    
                    
                       }",

                        ],

                ]);?>
            </div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>
        </div>

        <div class="form-group">
            <label class="col-sm-4" for="totalPrice"><?= Yii::t('app/number/index','Total amount')?></label>
            <div class="col-sm-7">
                <input class="form-control" name="totalPrice" readonly="readonly" type="text" id="totalPrice" value="<?= $model->price?>">
            </div>
            <div class="col-sm-1"></div>
            <span style="height:34px; width: 100px" class="help-block m-b-none"><div class="help-block"></div></span>

        </div>

        <div class="form-group">
            <label class="col-sm-4" for="status"></label>
            <div class="col-sm-7">
                <span onclick="return buyClick();return false" class="btn index-button-1" style="width: 100%;color: white" type="submit" ><?= Yii::t('app/number/index','Submit')?></span>
            </div>
        </div>
    </form>
            </div>

</div>

<script>
    function buyClick() {
        var moTime = $('#morenTime').html();
        var beginTimes = $('#callrecordsearch-begin_time').val();
        var endTimes = $('#callrecordsearch-end_time').val();
        var _btt = parseInt($('#begin_time_1').html());
        var _ett = parseInt($('#end_time_1').html());
        if(beginTimes == '' )
        {
            alert('<?=  Yii::t('app/number/index','Rent start time can not empty')?>');
            return false;
        }
        if(endTimes == '' )
        {
            alert('<?=  Yii::t('app/number/index','Rent end time can not empty')?>');
            return false;
        }

        if(beginTimes && endTimes)
        {
            var _dates = new Date(beginTimes.replace(/-/g, '/'));
            var _beginTimes = _dates.getTime()/1000;
            var _dates = new Date(endTimes.replace(/-/g, '/'));
            var _endTimes = _dates.getTime()/1000;
            if (_beginTimes < moTime ){
                alert('<?=  Yii::t('app/number/index','The rental start time can not be less than today')?>');
                $('#callrecordsearch-begin_time').val('')
                return false;
            }
            if(_beginTimes < _btt)
            {
                alert('<?=   Yii::t('app/number/index','The rental start time can not be less than the number to rent the start time')?>');
                $('#callrecordsearch-begin_time').val('')
                return false;
            }
            if(_beginTimes > _ett)
            {
                alert('<?=   Yii::t('app/number/index','The rental start time can not be greater than the number to rent the end time')?>');
                $('#callrecordsearch-begin_time').val('')
                return false;
            }
            if (_endTimes < moTime ){
                alert('<?= Yii::t('app/number/index','The rental end time can not be less than today')?>');
                $('#callrecordsearch-end_time').val('')
                return false;
            }

            if( _endTimes > _ett)
            {
                alert('<?= Yii::t('app/number/index','The rental end time can not be greater than the number to rent the end time')?>');
                $('#callrecordsearch-end_time').val('')
                return false;
            }
            var _day = (_endTimes - _beginTimes)/86400;
            var price = $('#price').val();
            var totalPrice = _day * price;
            $('#totalPrice').val(parseFloat(totalPrice).toFixed(4));



        }

        $('#buyForm').submit();
    }
</script>