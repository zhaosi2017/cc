<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/7/29
 * Time: 上午11:39
 * 这个文件 处理本地业务
 */
namespace app\modules\home\servers\appService;

class AppService{

    /**
     * @var AppService
     * 单例模式
     */
    private static $_instance;

    private function __construct()
    {
    }
    public static function init($className){

        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        if(class_exists($className)&& get_parent_class($className) == AbstractApp::class){
            self::$_instance->app = new $className();
        }else{
            self::$_instance= null;
        }
        return self::$_instance;
    }

    /**
     * @var AbstractApp
     * 使用的app类型
     */
    public $app;

    /**
     * @param $data
     * @return  mixed
     * 根据回调  处理业务
     */
    public function Event($data){
        if(empty($data)){
            return '';
        }
        $this->app->Event($data);
        switch ($this->app->callbcakData->CallBack_type){
            case AppCallBack::CALLBACK_TYPE_FIRST:
                $result = $this->_Welcome();
                break;
            case AppCallBack::CALLBACK_TYPE_MYSELF:
                $result = $this->_Myself();
                break;
            case AppCallBack::CALLBACK_TYPE_CONTACT:
                $result = $this->_Contact();
                break;
            case AppCallBack::CALLBACK_TYPE_MAP:
                $result = $this->_Map();
                break;
            case AppCallBack::CALLBACK_TYPE_BUTTON:
                $result = $this->_Button();
                break;
            case AppCallBack::CALLBACK_TYPE_REPLAY:
                $result = $this->_Replay();
                break;
            default:
                break;
        }

        return $result;

    }

    /**
     * 欢迎语
     */
    private function _Welcome(){
        $this->app->send_data->setSendData('欢迎来到呼叫中心');
        $this->app->Send();
    }

    /**
     * 分享自己的名片
     */
    private function _Myself(){
        if(empty($this->app->from_user->model_user)){   //还没有绑定 发送一个号码
           $code =  $this->app->setCode();
           $text = '你的验证码是: ' . $code;
           $this->app->send_data->setSendData($text);
        }else{                                          //绑定了的发送一个白名单 按钮
            if($this->app->from_user->model_user->whitelist_switch == 0){
                $data = [
                    'text' => '开启白名单',
                    'data' => implode('-', array(FuncService::FUNC_OPEN_WIHTSWITCH,
                                                        $this->app->from_user->app_uid,
                                                        $this->app->from_user->app_phone_number,
                                                        time())),
                ];
                $this->app->send_data->setSendData($data);
            }else{
                $data = [
                    'text' => '关闭白名单',
                    'data' => implode('-', array(FuncService::FUNC_COLSE_WIHTSWITCH,
                        $this->app->from_user->app_uid,
                        $this->app->from_user->app_phone_number,
                        time())),
                ];
                $this->app->send_data->setSendData($data);
            }
        }
        $this->app->Send();
    }
    /**
     * 分享了别人的名片
     */
    private function _Contact(){
        if(empty($this->app->to_user->model_user)){    //不是我们的会员
            $this->app->send_data->setSendData('他／她 还不是我们的会员，不能执行该操作');
        }else{
            $this->app->send_data->setSendData('操作菜单');
            $callMenu = [
                'text' => '呼叫',
                'data' => implode('-', array(FuncService::FUNC_CALL,
                                                    $this->app->from_user->app_uid,
                                                    $this->app->from_user->getName(),
                                                    $this->app->to_user->getName(),
                                                    time())),
            ];
            $this->app->send_data->setSendData($callMenu , 1);
            // 检查是否加了呼叫人到自己到白名单.
            $whiteRes = WhiteList::findOne(['uid' => $this->app->from_user->model_user->id,
                                            'white_uid'=> $this->app->to_user->model_user->id]);


            if ($whiteRes) {
                $whiteMenu = [
                    'text' => '移除白名单',
                    'data' => implode('-', array(FuncService::FUNC_REMOVIE_WIHTE,
                                                        $this->app->to_user->app_uid,
                                                        $this->app->to_user->app_phone_number,
                                                        time())),
                ];
            } else {
                $whiteMenu = [
                    'text' => '加入白名单',
                    'data' => implode('-', array(FuncService::FUNC_JOIN_WIHTE,
                                                        $this->app->to_user->app_uid,
                                                        $this->app->to_user->app_phone_number,
                                                        time())),
                ];
            }
            $this->app->send_data->setSendData($whiteMenu , 0);

            $blackRes = BlackList::findOne(['uid' => $this->app->from_user->model_user->id,
                'white_uid'=> $this->app->to_user->model_user->id]);
            // 黑名单按钮.
            if ($blackRes) {
                $blackMenu = [
                    'text' => $this->getUnblackText(),
                    'data' => implode('-', array(FuncService::FUNC_REMOVIE_BLACK,
                                                        $this->app->to_user->app_uid,
                                                        $this->app->to_user->app_phone_number,
                                                        time())),
                ];
            } else {
                $blackMenu = [
                    'text' => $this->getUnblackText(),
                    'data' => implode('-', array(FuncService::FUNC_JOIN_BLACK,
                                                        $this->app->to_user->app_uid,
                                                        $this->app->to_user->app_phone_number,
                                                        time())),
                ];
            }
            $this->app->send_data->setSendData($blackMenu , 0);
        }
        $this->app->Send();
    }

    /**
     * 地图查询
     */
    private function _Map(){}

    /**
     * 按钮事件回调
     */
    private function _Button(){

        if(empty($this->app->from_user->model_user)){
            $this->app->send_data->setSendData('你还不是我们的系统会员，请先完成绑定！');
            $this->app->Send();
            return false;
        }
        return FuncService::init($this->app);



    }

    /**
     * 回复某一条消息
     */
    private function _Replay(){

    }


}