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

    <?= $form->field($model, 'site_id')
             ->dropDownList(ArrayHelper::map(Site::find()
             ->all(),'id','name'),['prompt'=>'Все сайты']) ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model,'categories_id')->dropDownList(
        ArrayHelper::map(Сategories::find()->all(),
            'id','name'),
        ['prompt' => Yii::t('app','Все категории'),
            'onchange'=>' 
     $.get("'.Yii::$app->urlManager->createUrl('task-list/dropdown?id=').'"+$(this).val(), function(data) { 
     $("select#subcat").html(data); 
     })']
    ); ?>
    <?= $form->field($model, 'subcategories_id', ['inputOptions'=>['id'=>'subcat','class'=>'form-control']])->dropDownList([]); ?>

    <?php echo $form->field($model, 'min_price')->textInput(['style'=>'width:25%;']) ?>
    <div class="control-group">
        <div class="controls">
            <?php  echo $form->field($model, 'check_price')->checkbox(['value'   => 1]) ?>
            <?php  echo $form->field($model, 'check_time1')->checkbox(['value'   => (int)(time()-3600),'disabled' => false]) ?>
            <?php  echo $form->field($model, 'check_time3')->checkbox(['value'   => (int)(time()-10800)]) ?>
            <?php  echo $form->field($model, 'check_time6')->checkbox(['value'   => (int)(time()-21600)]) ?>
            <?php  echo $form->field($model, 'check_time7dn')->checkbox(['value' => (int)(time()-604800)]) ?>
            <?php echo empty($_POST['check_time1']) ? '' : ' checked="checked" '; ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6"><?= Html::submitButton('Поиск', ['class' => 'btn btn-danger' ]) ?></div>
        <div class="col-sm-6"><?= Html::resetButton ('Сброс', ['class' => 'btn btn-default']) ?></div>

    </div>
    <?php ActiveForm::end(); ?>
</div>

