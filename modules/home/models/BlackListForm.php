<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;
use app\modules\home\models\User;
use app\modules\home\models\BlackList;

/**
 * UserSearch represents the model behind the search form about `app\modules\home\models\User`.
 */
class BlackListForm extends Model
{
    public $account;

    public function rules()
    {
        return[
            ['account','string'],
            ['account','required'],
            ['account','checkAccount'],
        ];

    }

    public function attributeLabels()
    {
        return [
            'account' => '用户名',
        ];
    }

    public function createBlackList()
    {
        $blacklist = new BlackList();
        $blacklist->uid = Yii::$app->user->id;
        $blacklist->black_uid = $this->getUidByAccount($this->account);
        $res = BlackList::find()->where(['uid'=>$blacklist->uid,'black_uid'=>$blacklist->black_uid])->one();

        if(isset($res->id) && $res->id > 0){
            return $this->addError('account','你已经添加了该账号'.$this->account);
        }
        return $blacklist->save();
    }


    public  function getUidByAccount($account)
    {

        $rows = User::find()->select(['account'])->indexBy('id')->column();
        $id = '';
        foreach ($rows as $i => $v)
        {
            if($this->account == Yii::$app->security->decryptByKey(base64_decode($v), Yii::$app->params['inputKey']))
            {
                return	$id = $i;
                break;
            }
        }
        return $id;
    }

    public function checkAccount($attribute)
    {
        $rows = User::find()->select(['account'])->indexBy('id')->column();

        $accounts = [];
        $user = User::findOne(Yii::$app->user->id);
        foreach ($rows as $i => $v)
        {
            if($this->account == $user['account']){
                $this->addError($attribute, '用户不能添加自己为白名单');
                return;
                break;
            }
            $accounts[] = Yii::$app->security->decryptByKey(base64_decode($v), Yii::$app->params['inputKey']);

        }
        if(!in_array($this->account, $accounts)){
            $this->addError($attribute, '此账号不存在');
            return;
        }
    }
}