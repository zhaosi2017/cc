<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[LoginLogs]].
 *
 * @see LoginLogs
 */
class LoginLogsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LoginLogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LoginLogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
