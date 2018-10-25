<?php

namespace app\commands;

use app\models\FlParser;
use app\models\FreelanceParser;
use app\models\FreelansimParser;

use app\models\VkParser;
use yii\console\Controller;


class ParsController extends Controller
{
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