<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/2
 * Time: 下午2:57
 */
namespace app\modules\home\servers\appService;

class Send{
    /**
     * @var string 文本消息
     */
    public $text;
    /**
     * @var array ['text'=>'', 'data'=>'']
     */
    public $menu = [];



    /**
     * @param  int   $number  如果是添加菜单 需要指明添加的菜单的布局列 $number 代表列的序号
     * @param  mixed $data 发送的数据
     * @return mixed
     * 设定发送数据
     */
    public   function setSendData( $data , $number = 0){
        if(is_string($data) ){
            $this->text = $data;
        }elseif (is_array($data)){
            $this->menu[$number][] = $data;
        }
    }

}