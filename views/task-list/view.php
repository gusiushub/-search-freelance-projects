<?php

use app\models\Site;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = $model->title;

?>
<div class="task-view col-lg-offset-1 col-lg-10">

<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            ['label'  => 'Название сайта',
//                'value'  => function ($model) {
//        $site = Site::findOne($model['site_id']);
//                    //return $site['name'];
//                }
//            ],
//            'date',
            [
                'label'  => 'Дата',
                'value' => function($model){
                    $site = Site::findOne($model['site_id']);
                    echo "    <div class='media'>
                                 
                                 <div class='media-body'>
                                 
                                   <h3 style=' font-weight: 600' class='media-heading'>".$this->title."</h3>
                                   <hr>
                                   <a class='pull-right' href='#'>
                                    <img  align='right'  vspace='5' hspace='5'   class='media-object' src='../../web/img/".$site['logo']."' width='150x' height='150x' alt='...'>
                                   </a>".$model->text;// ? '<span class="text-success">Показывается</span>' : '<span class="text-danger">Не показывается</span>';

                    //return $data->text;// ? '<span class="text-success">Показывается</span>' : '<span class="text-danger">Не показывается</span>';
                    if ($model['price']==0):
                        echo "<br> <br><p class='media-left'><b>Оплата по договору</b></p>";
                    endif;
                    if ($model['price']!=0):
                        echo " <br><br><p class='media-left' style='font-size: large'>Цена:<b> ".$model['price']. "р</b></p>";
                    endif;
                    echo "<p style='text-align: right' class='media-right'>
<a href='".$model['url']."'>Перейти на сайт с объявлением</a>
</p>
</div>
</div>
<br>";
                    return $model['date'];
                }
            ],
//            [
//                'label'  => '',
//                'value'  => function ($model) {
////                    $site = Site::findOne($model['site_id']);
//                    if ($model['price']==0):
//                        echo "<br> <br><b><p>Оплата по договору</p></b>";
//                    endif;
//                    if ($model['price']!=0):
//                        echo " <br><br><p class='media-left' style='font-size: large'>Цена:<b> ".$model['price']. "р</b></p>";
//                    endif;
//                    echo "<p style='text-align: right' class='media-right'><a href='".$model['url']."'>Перейти на сайт с объявлением</a></p></div></div><br>";
//                    //return $model->title;
//                }
//            ],

//            'price',
        ],
    ]) ?>

</div>
