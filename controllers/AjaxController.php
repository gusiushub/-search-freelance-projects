<?php

namespace app\controllers;


use app\models\User;
use Yii;
use yii\web\Controller;

class AjaxController extends Controller
{
    public function actionSetting()
    {
        if(\Yii::$app->request->isAjax){
            $id = Yii::$app->user->id;
            $model = User::find()->where(['id' => $id])->one();
            $model->f_name = $_POST['f_name'];
            $model->s_name = $_POST['s_name'];
            $model->username = $_POST['login'];
            $model->email = $_POST['email'];
            $model->phone = $_POST['phone'];
            $model->save();
        }
    }
}