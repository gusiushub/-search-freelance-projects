<?php

namespace app\commands;

use app\models\FlParser;
use app\models\FreelancehuntComParser;
use app\models\FreelanceParser;

use app\models\FreelanceRuParser;
use app\models\FreelansimParserRu;
use app\models\Task;
use app\models\WeblancerNetParser;
use app\models\VkParser;
use Yii;
use yii\console\Controller;


class ParsController extends Controller
{
    /**
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $start = microtime(true);
        $this->actionFreelansim();
        $this->actionFreelancehunt();
        $this->actionWeblancer();
        $this->actionFreelance();
        $this->actionVk();
        $this->actionFl();
        echo "<br><br>Время выполнения: ".(microtime(true)-$start)." секунд.\n";
    }

    /**
     *
     */
    public function actionVk()
    {
        $robber = new VkParser();
    }
    /**
     * freelance.ru
     */
    public function actionFreelance()
    {
        $model = new FreelanceRuParser();
        $model->getUrlProgects(3);
    }

    /**
     *
     */
    public function actionWeblancer()
    {
        $parseUrl = new WeblancerNetParser();
        $parseUrl->getUrlProgects(2) ;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionFreelancehunt()
    {
        $model = new FreelancehuntComParser();
        $posts = $model->getProjectsByApi();

        foreach ($posts as $post) {
            $unic =Task::find()->where(['list_id' => $post['project_id']])->exists();
            if(!$unic){
                echo "Запись в бд нового поста \n";

				$unixTime = strtotime($post['publication_time']);
				$date = date('Y-m-d',$unixTime);
				$time = date('H:m:s',$unixTime);
			
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 6,
                    'title' => $post['name'],
                    'text' => $post['description'],
                    'price' => (int)$post['budget_amount'],
                    'list_id' => (int)$post['project_id'],
                    'url' => $post['url'],
                    'date' => $date,
                    'time' => $time,
                    'time_unix' => (int)(time()),
                ])->execute();
            }
        }
    }

    /**
     * freelansim.ru
     */
    public function actionFreelansim()
    {
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=marketing_smm',3,3);
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_backend',1,1);
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_frontend',1,2);
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=content_copywriting',6,5);
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_desktop',1,9);
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_other',6,11);
//        FreelansimParser::run('https://freelansim.ru/tasks?categories=testing_sites',5,12);
        $parseUrl = new FreelansimParserRu();
        $parseUrl->getUrlProgects(2,'development_all_inclusive');
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