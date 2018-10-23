<?php


namespace app\controllers;

use app\models\SettingForm;
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
            $model = new SettingForm();
            return $this->render('setting', [
                'model' => $model,
            ]);
        }
    }

}