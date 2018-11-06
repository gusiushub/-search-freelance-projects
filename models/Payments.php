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
}