<?php
require(__DIR__ . '/../vendor/autoload.php');
/**
* 邮件异步发送信息
*/
class MailServer
{
    const MAIL_USERNAME = 'officeaction2017@gmail.com';
    const MAIL_PASSWORD = 'Officeaction123';
    const MAIL_DOMAIN   = 'smtp.gmail.com';
    const MAIL_PORT     = 25;
    const MAIL_MODE     = 'tls';

    private $logger = null;
    private $server = null;
 
    public function __construct()
    {
        $this->server = new \swoole_server('127.0.0.1', 9503);
 
        $this->server->set([
            'worker_num' => 2,
            'daemonize' => true,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
        ]);
 
        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('Connect', [$this, 'onConnect']);
        $this->server->on('Receive', [$this, 'onReceive']);
        $this->server->on('Close', [$this, 'onClose']);
 
        $this->server->start();
    }
 
    public function onStart()
    {
        
    }
 
    public function onConnect($server, $descriptors, $fromId)
    {
        
    }
 
    public function onReceive(\swoole_server $server, $descriptors, $fromId, $data)
    {
        $msg = json_decode($data, true);
        $this->sendMail($msg['email'], $msg['verifyCode']);
    }
 
    public function onClose($server, $descriptors, $fromId)
    {
 
    }
 
    private function sendMail($email,  $verifyCode)
    {
        $transport = Swift_SmtpTransport::newInstance(self::MAIL_DOMAIN, self::MAIL_PORT, self::MAIL_MODE);
        $transport->setUsername(self::MAIL_USERNAME);
        $transport->setPassword(self::MAIL_PASSWORD);
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance();
        $message->setFrom([self::MAIL_USERNAME=>'呼叫中心系统']);
        $message->setTo($email);
        $message->setSubject('验证码');
        $message->setBody('验证码：'.$verifyCode);
        return $mailer->send($message); 
    }
 
}
 
$server = new MailServer();