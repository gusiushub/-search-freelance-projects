<?php


namespace app\controllers;

use yii\web\Controller;

class UserController extends Controller
{
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->render('index');
        }
    }

    public function actionSetting()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->render('setting');
        }
    }

}