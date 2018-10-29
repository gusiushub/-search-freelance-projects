<?php

namespace app\models;

use yii\db\ActiveRecord;


class Subcategories extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subcategories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categories_id'], 'safe'],
        ];
    }


}