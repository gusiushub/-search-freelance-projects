<?php

namespace app\models;

use yii\base\Model;

class HomePage extends Model
{
    public static function getRandTask()
    {
        $sites = self::getSite();
        $info = [];
        $k = 0;
        foreach ($sites as $site) {
             $info [$k]= Task::find()->where(['like','id',self::getRandIdTask($site['id'])])->one();
             $k++;
        }
        foreach ($sites as $site) {
            $info [$k]= Task::find()->where(['like','id',self::getRandIdTask($site['id'])])->one();
            $k++;
        }

        return $info;
    }

    public static function getSite()
    {
        return Site::find()->all();
    }

    public static function dayAgo()
    {

    }
    public static function getImg($id)
    {
        $site = Site::findOne($id);
        return $site['logo'];
    }

    public static function getRandIdTask($siteId)
    {
        $minId = Task::find()->where(['>', 'time_unix', time() - 86400])->andWhere(['like','site_id',$siteId])->min('id');
        $maxId = Task::find()->where(['>', 'time_unix', time() - 86400])->andWhere(['like','site_id',$siteId])->max('id');
//        var_dump($minId);
//        var_dump($maxId);
        return rand($minId, $maxId);
    }

}