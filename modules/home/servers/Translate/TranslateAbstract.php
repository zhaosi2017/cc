<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/10
 * Time: 上午10:18
 */
namespace app\modules\home\servers\Translate;
use Codeception\Lib\Connector\Guzzle;
use Codeception\Lib\Connector\Guzzle6;

abstract class TranslateAbstract{

    public function __construct($text = null, $des = null , $source = null)
    {
        $this->text = $text;
        $this->deration_Language = $des;
        $this->Source_Language = $source;
    }

    /**
     * @return mixed
     * 翻译文字
     */
    abstract public function Translate(Array $option = []);

    /**
     * @var string
     * 源语言
     */
    public $Source_Language;

    /**
     * @var string
     * 目标语言
     */
    public $deration_Language;
    /**
     * @var tring
     * 消息
     */
    public $text;




}