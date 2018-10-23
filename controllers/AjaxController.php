<?php

namespace app\controllers;

use app\models\SettingForm;
use app\models\User;
use Yii;
use yii\web\Controller;

class AjaxController extends Controller
{
    public function actionSetting()
    {
        if(\Yii::$app->request->isAjax){
//            $user = User::findOne(Yii::$app->user->id);//find()->where('id=:id',[':id'=>\Yii::$app->user->id])->one();
//            $user->f_name = $_POST['f_name'];
//            $user->s_name = $_POST['s_name'];
//            $user->usernamr = $_POST['login'];
//            $user->email = $_POST['email'];
//            $user->phone = $_POST['phone'];
//            $user->save();
//            $where = ['id' => Yii::$app->user->id];
//            User::updateAll(['f_name' => $_POST['f_name']]);
            // Номер Китая в таблице
            $id = Yii::$app->user->id;
// Запрос с выборкой Китая
            $model = User::find()->where(['id' => $id])->one();
// изменяем численность населения
            $model->f_name = $_POST['f_name'];
            $model->s_name = $_POST['s_name'];
            $model->username = $_POST['login'];
            $model->email = $_POST['email'];
            $model->phone = $_POST['phone'];
// сохраняем, можно использовать $model->update();
            $model->save();
        }

    }
}