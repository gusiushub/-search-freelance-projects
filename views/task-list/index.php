<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
//$this->params['breadcrumbs'][] = $this->title;


?>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div class="col-sm-9">
            <?php Pjax::begin(); ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_list',//function ($model, $key, $index, $widget) {
//                    return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
//                },
            ]) ?>
            <?php Pjax::end(); ?>
        </div>

    </div>
</div>


