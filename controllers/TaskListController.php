<?php

namespace app\controllers;

use app\models\Site;
use app\models\Subcategories;
use app\models\User;
use Yii;
use app\models\Task;
use app\models\TaskSearch;
use yii\bootstrap\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskListController implements the CRUD actions for Task model.
 */
class TaskListController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionDropdown($id=null, $subcategories_id=null)
    {

        $session = Yii::$app->session;
        var_dump($session->get('site'));

        if (isset($_GET['subcategories_id'])){

        }
        $k=0;
        if (isset($_GET['site_id'])){
            if (!$session->isActive){
                $session->open();
            }
            $site = Site::findOne($_GET['site_id']);
            $session->set('site', [$_GET['site_id']=>$site['name']]);
            //$session->close();
        }
        if (isset($_GET['id'])){

            $countPosts = Subcategories::find()
                ->where(['categories_id' => $id])
                ->count();

            $posts = Subcategories::find()
                ->where(['categories_id' => $id])
                ->orderBy('name ASC')
                ->all();
//            $posts= [
//                'FR'=>'France',
//                'DE'=>'Germany'
//            ];
            if($countPosts>0){
                foreach($posts as $post){
                    $name = $post->name;
                    $value = $post->id;
                    $checked = false;
                    echo $this->getLi($index=null, $label=null, $name, $checked, $value);
                }
                exit;
            }

        }

    }

    public function getLi ($index, $label, $name, $checked, $value)
    {
        return $arr ='<li >' . Html::checkbox($name, false, [
                'value' => $value,
                'label' => Html::encode($name),
            ]) . '</li>';

    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest and User::accessPermission()) {
            $searchModel = new TaskSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                //'subCat' => new Subcategories()
            ]);
        }else{
            return $this->redirect('/user/index');
        }
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new Task();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
