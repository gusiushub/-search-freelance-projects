<?php

namespace app\commands;

use app\models\Freelance;
use app\models\Parser;
use app\models\Task;
use phpQuery;
use Yii;
use yii\console\Controller;
use Dirst\Flrugrabber\FlGrabber;

class ParsController extends Controller
{
    /**
     * freelance.ru
     */
    public function actionFreelance()
    {
       $model = new Freelance();
       var_dump($model->freelance());

    }



    /**
     * freelansim.ru
     * @throws \yii\db\Exception
     */
    public function actionFreelansim()
    {
        echo "Начало \n";
        $parser = new Parser(['url' => 'https://freelansim.ru/tasks?categories=marketing_smm']);
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
                    'subcategories_id' => 3,
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

    /**
     * fl.ru
     * @throws \yii\db\Exception
     */
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
            if(!$unic){
                var_dump($unic);
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 1,
                    'subcategories_id' => 2,
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
                    'url' => 'https://fl.ru'.$job['link'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()),
                ])->execute();
            }
        }
    }
}