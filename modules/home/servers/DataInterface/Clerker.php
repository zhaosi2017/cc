<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/5
 * Time: 下午3:12
 */
namespace app\modules\home\servers\DataInterface;

class Clerker{
    /**
     * @var Server
     * websocket 对象
     */
    public $server;
    /**
     * @var fd
     * fd
     */
    public $fd;
    /**
     * @var array
     * 接口名称组
     */
    public $interface_array = [
        'login',
        'logout',
        ''

    ];
    /**
     * @param array $data
     * 处理数据 解析数据
     */
    public function analyze(Array $data){
        if(empty($data['method']) || in_array($data['method'] , $this->interface_array)){
            $this->server->push($this->fd , '数据错误，请求的接口不存在');
        }
        switch ($data['method']){
            case 'login':
                $this->_login();
                break;
            default :
                break;
        }
    }




}