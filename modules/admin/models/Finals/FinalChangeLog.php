<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/8/15
 * Time: 上午10:43
 *
 * 帐变记录
 */
namespace app\modules\admin\models\Finals;

use app\models\CActiveRecord;
use Yii;


class FinalChangeLog extends CActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'final_change_log';
    }













}