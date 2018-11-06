<?php

namespace app\controllers;

use app\models\Invoice;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class InterkassaController extends Controller
{
    public function actions() {
        return [
            'result' => [
                'class' => 'lan143\interkassa\ResultAction',
                'callback' => [$this, 'resultCallback'],
            ],
            'success' => [
                'class' => 'lan143\interkassa\SuccessAction',
                'callback' => [$this, 'successCallback'],
            ],
            'fail' => [
                'class' => 'lan143\interkassa\FailAction',
                'callback' => [$this, 'failCallback'],
            ],
        ];
    }

    public function actionInvoice()
    {
        $model = new Invoice();

        if ($model->load(Yii::$app->request) && $model->save())
        {
            $params = [
                'ik_pm_no' => $model->id,
                'ik_am' => $model->ammount,
                'ik_desc' => 'Site payment',
            ];

            return Yii::$app->interkassa->payment($params);
        }

        return $this->render('invoice', compact($model));
    }

    public function successCallback($ik_am, $ik_inv_st, $ik_pm_no)
    {
        return $this->render('success');
    }

    public function failCallback($ik_am, $ik_inv_st, $ik_pm_no)
    {
        return $this->render('fail');
    }

    public function resultCallback($ik_am, $ik_inv_st, $ik_pm_no)
    {

        switch ($ik_inv_st)
        {
            case 'new':
                $this->loadModel($ik_pm_no)->updateAttributes(['status' => Invoice::STATUS_NEW]);
                break;
            case 'waitAccept':
                $this->loadModel($ik_pm_no)->updateAttributes(['status' => Invoice::STATUS_PENDING]);
                break;
            case 'process':
                $this->loadModel($ik_pm_no)->updateAttributes(['status' => Invoice::STATUS_PROCESS]);
                break;
            case 'success':
                $this->loadModel($ik_pm_no)->updateAttributes(['status' => Invoice::STATUS_SUCCESS]);
                break;
            case 'canceled':
                $this->loadModel($ik_pm_no)->updateAttributes(['status' => Invoice::STATUS_CANCELED]);
                break;
            case 'fail':
                $this->loadModel($ik_pm_no)->updateAttributes(['status' => Invoice::STATUS_FAIL]);
                break;
        }
    }

    protected function loadModel($id)
    {
        $model = Invoice::findOne($id);

        if ($model === null)
            throw new BadRequestHttpException;

        return $model;
    }
}