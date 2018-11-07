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
            [[ 'user_id','price','paid_to'], 'required'],
        ];
    }
}