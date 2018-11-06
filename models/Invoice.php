<?php

namespace app\models;

use yii\db\ActiveRecord;


class Invoice extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }
}