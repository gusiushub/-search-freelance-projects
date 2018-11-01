<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Dirst\Flrugrabber\FlGrabber;


class FlParser extends Model
{
    public static function run($arr=[], $arr2=[],$category,$subCutegory)
    {
        $FlGrabber = new FlGrabber('/');
        $jobs = $FlGrabber->getFilteredJobs($arr,$arr2);
        $field = Task::find()->where('site_id=:site_id',[':site_id'=>1])->groupBy('id')->orderBy('id DESC')->one();
        $field = $field['list_id'];//вывод последнего id из бд
        foreach ($jobs as $job){
            $unic =Task::find()->where(['list_id' => $job['id']])->exists();
            if(!$unic){
                echo "Запись в бд нового поста \n";
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 1,
                    'categories_id' => $category,
                    'subcategories_id' => $subCutegory,
                    'title' => $job['title'],
                    'text' => $job['description'],
                    'price' => trim($job['budget']),
                    'list_id' => $job['id'],
                    'url' => 'https://fl.ru'.$job['link'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()),
                ])->execute();
            }
        }
        echo "Завершено \n";
    }
}