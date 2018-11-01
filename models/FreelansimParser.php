<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FreelansimParser extends Model
{
    /**
     * @param $url
     * @param $categories_id
     * @param $subcategories_id
     * @throws \yii\db\Exception
     */
    public static function run($url, $categories_id, $subcategories_id)
    {
        echo "Начало \n";
        $parser = new Parser(['url' => $url]);//'https://freelansim.ru/tasks?categories=marketing_smm'
        $jobs = $parser->parse() ;
        foreach ($jobs as $job){
            $price = str_replace([' ','руб.','за','час','месяц','проект'], "", $job['price']);
            $price = (int)$price['price'];
            //var_dump();
            echo "Цикл \n";
            $unic =Task::find()->where(['list_id' => $job['id']])->exists();
            if(!$unic){
                echo '+';
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 3,
                    'subcategories_id' => $subcategories_id,
                    'categories_id' => $categories_id,
                    'title' => $job['title'],
//                    'text' => $job['description'],
                    'price' => $price,
                    'list_id' => $job['id'],
                    'url' => 'https://freelansim.ru'.$job['link'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()),
                ])->execute();
            }

        }
        echo "Конец \n";
    }
}