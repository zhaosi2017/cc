<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/2
 * Time: 下午4:58
 * 这个处理自定义的回调按钮事件
 */
namespace app\modules\home\servers\appService;

use app\modules\home\models\BlackList;
use app\modules\home\models\CallRecord;
use app\modules\home\models\WhiteList;
use app\modules\home\servers\TTSservice\Sinch;
use app\modules\home\servers\TTSservice\TTSservice;
use Symfony\Component\Yaml\Tests\B;

class FuncService{


    const FUNC_OPEN_WIHTSWITCH =  'cc_whiteswitch';
    const FUNC_COLSE_WIHTSWITCH = 'cc_unwhiteswitch';
    const FUNC_CALL             = 'cc_call';
    const FUNC_CALL_CONTACT     = 'cc_call_urgent';
    const FUNC_REMOVIE_WIHTE    = 'cc_unwihte';
    const FUNC_JOIN_WIHTE       = 'cc_wihte';
    const FUNC_REMOVIE_BLACK    = 'cc_unblack';
    const FUNC_JOIN_BLACK       = 'cc_black';

    /**
     * @param array $data
     * @param AbstractApp $app_obj
     */
    public static function init( $app_obj)
    {
        $data = $app_obj->callbcakData->callback_data->getArrayData();
        switch ($data[0]){
            case self::FUNC_REMOVIE_BLACK:
                $result = self::removie_black($app_obj);
                break;
            case self::FUNC_JOIN_BLACK:
                $result = self::join_black($app_obj);
                break;
            case self::FUNC_OPEN_WIHTSWITCH:
                $result = self::open_wihte($app_obj);
                break;
            case self::FUNC_COLSE_WIHTSWITCH:
                $result = self::colse_wihte($app_obj);
                break;
            case self::FUNC_CALL:
                $result = self::call($app_obj);
                break;
            case self::FUNC_CALL_CONTACT:
                $result = self::call_contact($app_obj);
                break;
            case self::FUNC_JOIN_WIHTE:
                $result = self::join_wihte($app_obj);
                break;
            case self::FUNC_REMOVIE_WIHTE:
                $result = self::removie_wihte($app_obj);
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }

    /**
     * @param AbstractApp $app_obj
     * 开启白名单
     */
    public static function open_wihte($app_obj){
        $model = $app_obj->from_user->model_user;
        $model->whitelist_switch = 1;
        return $model->save();
    }
    /**
     * @param AbstractApp $app_obj
     * 关闭白名单
     */
    public static function colse_wihte($app_obj){
        $model = $app_obj->from_user->model_user;
        $model->whitelist_switch = 0;
        return $model->save();
    }
    /**
     * @param AbstractApp $app_obj
     * 呼叫联系电话
     */
    public static function call($app_obj){
        if(self::_check_contact($app_obj)
                && self::_check_black($app_obj)
                && self::_chek_wihte($app_obj)
                && self::_check_call_limit($app_obj)) {

            $call = TTSservice::init(Sinch::class);
            $call->sendMessage(CallRecord::Record_Type_none , $app_obj);
        }

    }
    /**
     * @param AbstractApp $app_obj
     * 呼叫紧急联系人
     */
    public static function call_contact($app_obj){
        if(self::_check_contact($app_obj)
            && self::_check_black($app_obj)
            && self::_chek_wihte($app_obj)
            && self::_check_call_limit($app_obj)) {

            $call = TTSservice::init(Sinch::class);
            $call->sendMessage(CallRecord::Record_Type_emergency , $app_obj);
        }
    }

    /**
     * @param AbstractApp $app_obj
     * 加入白名单
     */
    public static function join_wihte($app_obj){
       $model =  WhiteList::findOne(['uid'=>$app_obj->from_user->model_user->id , 'wihte_uid'=>$app_obj->to_user->model_user->id]);
        if(empty($model)){
            $model = new WhiteList();
            $model->uid = $app_obj->from_user->model_user->id;
            $model->wihte_uid = $app_obj->to_user->model_user->id;
            $model->save();
        }
        return true;
    }

    /**
     * @param AbstractApp $app_obj
     * 移除白名单
     */
    public static function removie_wihte($app_obj){
        $model = BlackList::findOne(['uid'=>$app_obj->from_user->model_user->id , 'wihte_uid'=>$app_obj->to_user->model_user->id]);
        if(!empty($model)){
            $model->delete();
        }
        return true;
    }

    /**
     * @param AbstractApp $app_obj
     * 加入黑名单
     */
    public static function join_black($app_obj){
        $model = BlackList::findOne(['uid'=>$app_obj->from_user->model_user->id , 'black_uid'=>$app_obj->to_user->model_user->id]);
        if(empty($model)){
            $model = new BlackList();
            $model->uid = $app_obj->from_user->model_user->id;
            $model->black_uid = $app_obj->to_user->model_user->id;
            $model->save();
        }
        return true;
    }

    /**
     * @param AbstractApp $app_obj
     * 移除黑名单
     */
    public static function removie_black($app_obj){
        $model = BlackList::findOne(['uid'=>$app_obj->from_user->model_user->id , 'black_uid'=>$app_obj->to_user->model_user->id]);
        if(!empty($model)){
            $model->delete();
        }
        return true;
    }


    /**
     * 联系人检测
     * 检测联系人是不是我们的会员
     * @param AbstractApp $app_obj
     */
    private static function _check_contact($app_obj){
        if(empty($app_obj->to_user->model_user)){
            $app_obj->send_data->setSendData($app_obj->to_user->getName().' 还不是我们的会员，不能执行该操作！');
            $app_obj->Send();
            return false;
        }
        return true;
    }

    /**
     * 白名单检测
     * @param AbstractApp $app_obj
     */
    private static function _chek_wihte($app_obj){
        if($app_obj->to_user->model_user->whitelist_switch){   //开启白名单
                $model = WhiteList::findOne(['uid'=>$app_obj->to_user->model_user->id ,
                                             'wihte_uid'=>$app_obj->from_user->model_user->id]);
                if(empty($model)){
                    $app_obj->send_data->setSendData('你不在'.$app_obj->to_user->getName().' 的白名单内，不能发起呼叫！');
                    $app_obj->Send();
                    return false;
                }
        }
        return true;
    }
    /**
     * 黑名单检测
     * @param AbstractApp $app_obj
     */
    private static function _check_black($app_obj){

        $model = BlackList::findOne(['uid'=>$app_obj->to_user->model_user->id,
                                    'black_uid'=>$app_obj->from_user->model_user->id]);
        if(!empty($model)){
            $app_obj->send_data->setSendData('你在'.$app_obj->to_user->getName().' 的黑名单内，不能发起呼叫！');
            $app_obj->Send();
            return false;
        }
        return true;
    }
    /**
     * 呼叫限制检测
     * @param AbstractApp $app_obj
     */
    private static function _check_call_limit($app_obj){
        if ($app_obj->to_user->model_user->long_time && $app_obj->to_user->model_user->un_call_number) {
            $cacheKey =$app_obj->to_user->model_user->id;
            $callKey = $app_obj->to_user->model_user->country_code.$app_obj->to_user->model_user->phone_number;
            if (!Yii::$app->redis->exists($cacheKey)) {
                Yii::$app->redis->hset($cacheKey, 'total', 1);
                Yii::$app->redis->hset($cacheKey, $callKey, 1);
                Yii::$app->redis->expire($cacheKey, $app_obj->to_user->model_user->long_time * 60);
            } else {
                $totalNum = Yii::$app->redis->hget($cacheKey, 'total');
                $personNum = Yii::$app->redis->hexists($cacheKey, $callKey) ? Yii::$app->redis->hget($cacheKey, $callKey) : 0;
                if ($totalNum >= $app_obj->to_user->model_user->un_call_number || $personNum >= $app_obj->to_user->model_user->un_call_by_same_number) {
                    $app_obj->send_data->setSendData('超出呼叫设置限制，请稍候再试！');
                    $app_obj->Send();
                    return false;
                }
                Yii::$app->redis->hincrby($cacheKey, 'total', 1);
                Yii::$app->redis->hexists($cacheKey, $callKey) ? Yii::$app->redis->hincrby($cacheKey, $callKey, 1) : Yii::$app->redis->hset($cacheKey, $callKey, 1);
            }
        }
        return true;
    }

}