<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[CallRecord]].
 *
 * @see CallRecord
 */
class CallRecordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CallRecord[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CallRecord|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
