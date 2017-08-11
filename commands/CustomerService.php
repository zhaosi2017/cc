<?php
namespace app\commands;
use app\modules\home\models\PotatoMap;
class CustomerService{
    public $server = null;
    //用户的连接组
    public  $userconns ;
    //客服的连接组
    public   $customers;
    //用户--客服
    public $user_customers;
    //客服下 对应的用户数量
    public $customer_nums ;
    //已被使用的客服
    public $_customer ;
    //发送的内容
    public $content;

    public function __construct()
    {
        if($this->server === null) {
            $this->server = new \swoole_websocket_server("127.0.0.1", 9507);
        }
        return $this->server;
    }

    public function run()
    {
        $this->server->on('Message',[$this,'Message']);
        $this->server->on('Close',[$this,'Close']);
        $this->server->set([
            'worker_num' => 1,
            'daemonize' => false,
            'log_file' => "/tmp/swoole.log",
            'debug_mode'=> 1,
        ]);
        $this->server->start();
    }


    public function Message(\swoole_websocket_server $server,$frame)
    {

        $this->server->send($frame->fd,'hahhahhahahhahaha');
        echo 'from '.$frame->fd.' | data:'.$frame->data.PHP_EOL;
        $data = json_decode($frame->data,true);
        $data['time'] = date('Y-m-d H:i:s');
        echo 'type : '.$data['type'].PHP_EOL;
        switch ($data['type']){
            case 'login':
                $this->user_login($frame->fd,$data);
                break;
            case 'customer_login':
                $this->customer_login($frame->fd,$data);
                break;
            case 'say':

                $this->say($frame->fd,$data);
                break;
            case 'customer_say':
                $this->customer_say($frame->fd,$data);
                break;
            default:
                break;
        }
        echo '---------------------------------------------'.PHP_EOL;
    }


    public function Close(\swoole_websocket_server $server, $fd)
    {

        if(isset($this->userconns[$fd]))
        {
            unset($this->userconns[$fd]);
        }

        if(isset($this->user_customers[$fd]))
        {
            unset($this->user_customers[$fd]);
        }

        if(!empty($this->user_customers))
        {
            foreach ($this->user_customers as $v => $customer)
            {
                if($customer == $customer)
                {
                    unset($this->user_customers[$v]);
                }
            }
        }

        if(isset($this->customers[$fd]))
        {
            unset($this->customers[$fd]);
        }

        $this->server->push($fd,'close');

    }

    private function user_login($fd,$data)
    {
        $this->userconns[$fd]  = $data['userid'];
        $this->content = ['msg'=>'hello world','userid' => $data['userid'],'time'=>$data['time']];
        $this->server->push($fd, $this->getContent());
        if(empty($this->customers)){
            $this->content = ['客服不在线'];
            $this->server->push($fd,'客服不在线');
        }else{

            $this->user_customers[$fd] = $_custmer=array_rand($this->customers,1);
            $this->server->push($_custmer,json_encode(['msg'=>$data['userid'].' 用户连接上','userid' => $data['userid'],'time'=>$data['time']]));
        }


    }

    private function getContent()
    {
        return json_encode($this->content);
    }

    private function customer_login($fd,$data)
    {
        $this->customers[$fd] = $data['userid'];
        $this->server->push($fd,json_encode(['msg'=>'hello world','userid' => $data['userid'],'time'=>$data['time']]));
    }

    private function say($fd,$data)
    {
        $content = $data['data'];
        $potaoMap = new PotatoMap();
        $res = $potaoMap->findOne(6);
        file_put_contents('/tmp/swoole.log',var_export($res,true).PHP_EOL,8);
        $this->server->push($fd,json_encode(['msg'=>$content,'userid'=>$data['userid'],'time'=>$data['time'],'map'=>$res]));
        if(isset($this->user_customers[$fd]))
        {
            $this->server->push($this->user_customers[$fd],json_encode(['msg'=>$content,'userid'=>$data['userid'],'time'=>$data['time']]));
        }

    }

    private function customer_say($fd,$data)
    {
        $this->server->push($fd, json_encode(['msg' => $data['data'], 'userid' => $data['userid'],'time'=>$data['time']]));
        if (!empty($this->user_customers)) {
            foreach ($this->user_customers as $k => $customer) {
                if ($fd == $customer) {
                    $this->server->push($k, json_encode(['msg'=>$data['data'],'userid'=>$data['userid'],'time'=>$data['time']]));
                    break;
                }
            }
        }
    }
}



$server = new CustomerService();
$server->run();



?>