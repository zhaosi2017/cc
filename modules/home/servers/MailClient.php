<?php 
namespace app\modules\home\servers;

use Yii;
/**
 * 异步邮件发送客户端
 */
class MailClient
{
    private $client;
 
    public function __construct() {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
    }
 
    public function connect() {
        if (!$this->client->connect('127.0.0.1', 9503, 1)) {
            throw new Exception(sprintf('Swoole Error: %s', $this->client->errCode));
        }
    }
 
    public function send($data){
        if ($this->client->isConnected()) {
            if (!is_string($data)) {
                $data = json_encode($data);
            }
            return $this->client->send($data);
        } else {
            throw new Exception('Swoole Server does not connected.');
        }
    }
 
    public function close()
    {
        $this->client->close();
    }
}


 ?>