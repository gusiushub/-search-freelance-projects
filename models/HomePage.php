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
             $info [$k]= Task::find()->where(['like','id',self::getRandIdTask($site['id'])])->orderBy('id DESC')->one();
             $k++;
        }
        foreach ($sites as $site) {
            $info [$k]= Task::find()->where(['like','id',self::getRandIdTask($site['id'])])->orderBy('id DESC')->one();
            $k++;
        }

        return $info;
    }

    /**
     *  Возвращает info для всех сайтов из бд
     *
     * @return array|\yii\db\ActiveRecord[]
     */
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

    /**
     * возвращает случайный id поста из таблицы task
     *
     * @param $siteId
     * @return int
     */
    public static function getRandIdTask($siteId)
    {
        $minId = Task::find()->where(['>', 'time_unix', time() - 86400])->andWhere(['like','site_id',$siteId])->min('id');
        $maxId = Task::find()->where(['>', 'time_unix', time() - 86400])->andWhere(['like','site_id',$siteId])->max('id');
        return rand($minId, $maxId);
    }

}