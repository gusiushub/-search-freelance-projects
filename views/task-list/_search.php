<?php

use app\models\Site;
use app\models\Сategories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\TaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="control-group">

        <?= $form->field($model, 'site_id', ['template' => "
                                                <div class='dropdown'>
                                                    <button
                                                    class='btn btn-default dropdown-toggle'
                                                    data-toggle='dropdown'
                                                    type='button'>
                                                    <span>Все сайты</span>
                                                    <span class='caret'></span>
                                                    </button>
                                                    {input}
                                                </div>"])->checkboxList((ArrayHelper::map(Site::find()->all(),'id','name')), ['tag' => 'ul',
                'separator' => '<br>',
                'class' => 'dropdown-menu',]);
$posts= [
    'FR'=>'France',
    'DE'=>'Germany'
];

 ?>

        <?= $form->field($model, 'categories_id', ['template' => "
                                                <div class='dropdown'>
                                                    <button
                                                    class='btn btn-default dropdown-toggle'
                                                    data-toggle='dropdown'
                                                    type='button'>
                                                    <span>Категории</span>
                                                    <span class='caret'></span>
                                                    </button>
                                                    {input}
                                                </div>"])->checkboxList(ArrayHelper::map(Сategories::find()->all(),
            'id','name'),
            ['tag' => 'ul',
'separator' => '<br>',
                'class' => 'dropdown-menu',]); ?>


    <?= $form->field($model, 'title')->textInput(['placeholder'=>'Ключевое слово']) ?>




    <?php echo $form->field($model, 'min_price')->textInput(['style'=>'width:25%;']) ?>

        <div class="controls">
            <?php  echo $form->field($model, 'check_price')->checkbox(['value'   => 1]) ?>
            <?php  echo $form->field($model, 'check_time1')->checkbox(['value'   => (int)(time()-3600)]) ?>
            <?php  echo $form->field($model, 'check_time3')->checkbox(['value'   => (int)(time()-10800)]) ?>
            <?php  echo $form->field($model, 'check_time6')->checkbox(['value'   => (int)(time()-21600)]) ?>
            <?php  echo $form->field($model, 'check_time7dn')->checkbox(['value' => (int)(time()-604800)]) ?>
        </div>
    </div>
    <div  class="text-centr">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-danger',"style"=>"margin: 5px; width:30%" ]) ?>
        <?= Html::resetButton ('Сброс', ['class' => 'btn btn-default',"style"=>"margin: 5px; width:30% "]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


