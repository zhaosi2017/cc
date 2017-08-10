<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/5
 * Time: 下午2:46
 * 数据接口服务
 */
namespace app\modules\home\servers\DataInterface;

class Server{

    public $server;
    public $Clerk;       // 业务处理对象

    public function __construct(){

        $this->server = new Swoole_websocket_server('127.0.0.1', 9803);

        $this->server->set([
            'worker_num' => 2,
            'daemonize' => true,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
        ]);
        $this->server->on('Message', [$this, 'onMessage']);
        $this->server->on('colse' ,[$this , 'onColse']);

        $this->server->start();
    }

    /**
     * @param swoole_server $server
     * @param swoole_websocket_frame $frame
     * 这里作为数据入口
     */
    public function onMessage(swoole_server $server, swoole_websocket_frame $frame){
        $this->Clerk = new Clerker();
        $this->Clerk->server = $server;
        $this->Clerk->fd = $frame;

        $data = $frame->data;
        $data = json_decode($data , true);
        /**
         * 身份校验
         */
        if(empty($data)){
            $this->server->push($frame->fd , '数据错误');
        }
         $this->Clerk->analyze($data);
    }


    public function onColse( swoole_server $server,  $fd){



    }

}