<?php

namespace app\commands;

use app\models\FlParser;
use app\models\FreelancehuntComParser;
use app\models\FreelanceParser;
use app\models\FreelansimParser;

use app\models\Task;
use app\models\WeblancerNetParser;
use app\models\VkParser;
use Yii;
use yii\console\Controller;


class ParsController extends Controller
{
    public function actionIndex()
    {
        $this->actionFreelansim();
        $this->actionFl();
        $this->actionFreelancehunt();
        $this->actionWeblancer();
        $this->actionFreelance();
    }

    public function actionVk()
    {
        $robber = new VkParser();
        //var_dump($robber);

    }
    /**
     * freelance.ru
     */
    public function actionFreelance()
    {
       $model = new FreelanceParser();
       $model->freelance();
    }

    public function actionWeblancer()
    {
        $parseUrl = new WeblancerNetParser();
        $parseUrl->getUrlProgects(2) ;
    }

    public function actionFreelancehunt()
    {
        $model = new FreelancehuntComParser();
        $posts = $model->getProjectsByApi();
        foreach ($posts as $post) {
            $unic =Task::find()->where(['list_id' => $post['project_id']])->exists();
            if(!$unic){
                echo "Запись в бд нового поста \n";
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 1,
//                    'categories_id' => $category,
//                    'subcategories_id' => $subCutegory,
//                    'title' => $job['title'],
                    'text' => $post['description'],
//                    'price' => trim($job['budget']),
                    'list_id' => $post['project_id'],
                    'url' => 'https://fl.ru'.$post['url'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()),
                ])->execute();
            }
//            echo "<br>"; echo "<br>"; echo "<br>";
//            var_dump($post);
//            echo "<br>"; echo "<br>"; echo "<br>";
//            var_dump($post['skills']);
//            echo "<br>"; echo "<br>"; echo "<br>";
//            var_dump($post['project_id']);
//            echo "<br>"; echo "<br>"; echo "<br>";
//            var_dump($post['url']);
//            echo "<br>"; echo "<br>"; echo "<br>";
//            var_dump($post['description']);
//            echo "<br>"; echo "<br>"; echo "<br>";
//            var_dump($post['description_html']);
//            echo "<br>"; echo "<br>"; echo "<br>";
        }

//        var_dump($model->parseProjects(2));

    }

    /**
     * freelansim.ru
     */
    public function actionFreelansim()
    {
        FreelansimParser::run('https://freelansim.ru/tasks?categories=marketing_smm',3,3);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_backend',1,1);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_frontend',1,2);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=content_copywriting',6,5);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_desktop',1,9);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_other',6,11);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=testing_sites',5,12);
    }

    /**
     * fl.ru
     */
    public function actionFl()
    {
        FlParser::run([9],[2],1,1);
        FlParser::run([93],[14],1,1);
        FlParser::run([43],[1],6,5);
        FlParser::run([104],[1],6,5);

    }
}