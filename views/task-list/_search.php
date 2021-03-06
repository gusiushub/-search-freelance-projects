<?php

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

<!--    --><?//= $form->field($model, 'id') ?>

    <?= $form->field($model, 'site_id')->dropDownList(ArrayHelper::map(\app\models\Site::find()->all(),'id','name'),['prompt'=>'Выбрать сайт']) ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'subcategories_id')->dropDownList(ArrayHelper::map(\app\models\Сategories::find()->all(),'id','name'),['prompt'=>'Выбрать категорию']) ?>

<!--    --><?//= $form->field($model, 'date') ?>

<!--    --><?//= $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'price') ?>
    <?php  echo $form->field($model, 'min_price') ?>
    <?php  echo $form->field($model, 'max_price') ?>
    <?php  echo $form->field($model, 'check_price')->checkbox([ 'value' => 0])->label('По договоренности') ?>
    <?php  echo $form->field($model, 'check_time1')->checkbox(['value'=>(int)(time()/3600)-3600,'checked'=>false]) ?>
    <?php  echo $form->field($model, 'check_time3')->checkbox(['value' => (int)(time()/3600)-10800]) ?>
    <?php  echo $form->field($model, 'check_time6')->checkbox(['value' => (int)(time()/3600)-21600]) ?>
    <?php  echo $form->field($model, 'check_time7dn')->checkbox(['value' => (int)(time()/3600)-604800]) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
