<?php

namespace app\models;



/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $site_id
 * @property string $title
 * @property string $date
 * @property string $text
 * @property string $status
 * @property int $price
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_id', 'title', 'date', 'text', 'status', 'price','subcategories_id'], 'required'],
            [['site_id', 'price','subcategories_id'], 'integer'],
            [['title', 'text'], 'string'],
            [['date','subcategories_id'], 'safe'],
            [['status'], 'string', 'max' => 32],
        ];
    }

    /**
     * @return bool
     */
    public function setError()
    {
//        $this->name = 'Не удалось спарсить';
//        $this->parse = self::PARSE_ERROR;
//        return $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Сайт',
            'title' => '',
//            'title' => 'Ключевое слово',
            'date' => 'Date',
            'text' => 'Text',
            'status' => 'Status',
            'price' => 'Price',
            'subcategories_id' => '',
//            'subcategories_id' => 'Категория',
        ];
    }

    public function saveTask()
    {
        return Task::find()->one();
    }
}
