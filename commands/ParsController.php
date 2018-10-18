<?php

namespace app\commands;

use app\models\Task;
use Yii;
use yii\console\Controller;
use Dirst\Flrugrabber\FlGrabber;

class ParsController extends Controller
{
    public function actionFl()
    {
        $arr = [2];
        $arr2 = [9];
        $FlGrabber = new FlGrabber('/');
        $jobs = $FlGrabber->getFilteredJobs($arr2,$arr);
        $field = Task::find()->where('site_id=:site_id',[':site_id'=>1])->orderBy('id DESC')->one();
        $field = $field['list_id'];//вывод последнего id из бд

        foreach ($jobs as $job){
            $unic =Task::find()->where(['list_id' => $job['id']])->exists();
           // $unic = Task::find()->where('list_id=:list_id',[':list_id'=>$job['id']])->one();
            //var_dump($job["budget"]);
            if(!$unic){
               // $chas = gmp_strval(gmp_divexact(time(),3600));
                var_dump($unic);
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 1,
                    'subcategories_id' => 2,
                    'title' => $job['title'],
                    'text' => $job['description'],
                    'price' => trim($job['budget']),
                    'list_id' => $job['id'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()/3600),
                ])->execute();
            }
        }
        $arr3 = [14];
        $arr4 = [93];
        $FlGrabber = new FlGrabber('/');
        $jobs = $FlGrabber->getFilteredJobs($arr4,$arr3);
        $field = Task::find()->where('site_id=:site_id',[':site_id'=>1])->orderBy('id DESC')->one();
        $field = $field['list_id'];//вывод последнего id из бд

        foreach ($jobs as $job){
            //var_dump($job);
            $unic =Task::find()->where(['list_id' => $job['id']])->exists();
            // $unic = Task::find()->where('list_id=:list_id',[':list_id'=>$job['id']])->one();

            if(!$unic){
               // $chas = gm//p_strval(gmp_divexact(time(),3600));
                var_dump($unic);
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 1,
                    'subcategories_id' => 1,
                    'title' => $job['title'],
                    'text' => $job['description'],
                    'price' => trim($job['budget']),
                    'list_id' => $job['id'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()/3600),
                ])->execute();
            }
        }
        //var_dump($field) ;
    }
}