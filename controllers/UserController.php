<?php


namespace app\controllers;

use app\models\SettingForm;
use app\models\User;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
//            $user = new User();
            //$user->trialPeriod();
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

    public function actionPay()
    {
        return $this->render('pay');
    }

}