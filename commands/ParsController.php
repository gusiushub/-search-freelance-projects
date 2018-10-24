<?php

namespace app\commands;

use app\models\FlParser;
use app\models\Freelance;
use app\models\FreelansimParser;
use yii\console\Controller;


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
        FreelansimParser::run('https://freelansim.ru/tasks?categories=marketing_smm',3,3);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_backend',1,1);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_frontend',1,2);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=content_copywriting',6,5);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_desktop',1,9);
        FreelansimParser::run('https://freelansim.ru/tasks?categories=development_other',1,10);
    }

    /**
     * fl.ru
     * @throws \yii\db\Exception
     */
    public function actionFl()
    {
        FlParser::run([9],[2],1,1);
        FlParser::run([93],[14],1,1);
        FlParser::run([43],[1],6,5);
        FlParser::run([104],[1],6,5);

    }
}