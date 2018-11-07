<?php

namespace app\models;

use yii\db\ActiveRecord;


class Payments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['ik_co_id', 'user_id', 'date', 'ik_inv_id', 'status', 'cod','ik_co_id'], 'required'],
        ];
    }
}