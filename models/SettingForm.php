<?php

namespace app\models;

use yii\base\Model;

class SettingForm extends Model
{
    public $f_name;
    public $s_name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['f_name', 's_name'], 'string'],
        ];
    }

}