<?php

use app\models\Site;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = $model->title;

?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            ['label'  => 'Название сайта',
                'value'  => function ($model) {
        $site = Site::findOne($model['site_id']);
                    return $site['name'];}],

            ['label'  => 'title',
                'value'  => function ($model) {
//                    $site = Site::findOne($model['site_id']);
                    echo $model['title']."<br>Ссыдка на пост  <a href='".$model['url']."'>".$model['url']."</a>";
                    return $model->title;
    }],
            'date',
            [
                'attribute' => 'text',
                'format' => 'raw',
                'value' => function($data){
                    return $data->text ? '<span class="text-success">Показывается</span>' : '<span class="text-danger">Не показывается</span>';
                }
            ],
            'price',
        ],
    ]) ?>

</div>
