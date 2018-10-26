<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';


?>

<div class="container">
    <div class="task-index">

            <aside  class="column_sidebar">
            <div  class="col-lg-3 col-sm-3" >
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
    </aside>
            <div class="column_main">
                <div  class="col-lg-9 col-sm-9">
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
</div>



