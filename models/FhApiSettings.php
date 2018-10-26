<?php
namespace app\models;
use Yii;
/**
 * This is the model class for table "fh_api_settings".
 *
 * @property int $id
 * @property string $api_token
 * @property string $secret_key
 */
class FhApiSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fh_api_settings';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['secret_key'], 'required'],
            [['api_token', 'secret_key'], 'string', 'max' => 255],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'api_token' => 'Идентификатор',
            'secret_key' => 'Секретный ключ',
        ];
    }
}