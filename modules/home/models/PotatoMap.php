<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\CallRecord;

class PotatoMap extends \app\models\CActiveRecord
{
/**
 * This is the model class for table "potato_map".
 *
 * @property integer $id
 * @property string  $title;
 * @property string  $description;
 * @property string  $address;
 * @property integer $chat_id;
 * @property string  $latitude;
 * @property string  $longitude;
*/
    public static function tableName()
    {
        return 'potato_map';
    }





}