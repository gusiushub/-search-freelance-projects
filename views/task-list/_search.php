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
    <?= $form->field($model, 'site_id')
             ->dropDownList(ArrayHelper::map(Site::find()
             ->all(),'id','name'),['prompt'=>'Все сайты','onchange'=>' 
     $.get("'.Yii::$app->urlManager->createUrl('task-list/dropdown?site_id=').'"+$(this).val(), function(data) { 
     
     })']) ?>
        <?php
//        $session = Yii::$app->session;
//        foreach ($session['site'] as $value) {  ?>
<!--        <span class="label label-danger">--><?php //echo $value ?><!--</span>-->
<?php // } ?>
    <?= $form->field($model, 'title')->textInput(['placeholder'=>'Ключевое слово']) ?>

    <?= $form->field($model,'categories_id')->dropDownList(
        ArrayHelper::map(Сategories::find()->all(),
            'id','name'),
        ['prompt' => Yii::t('app','Все категории'),
            'id'=>'cat',
            'onchange'=>' 
     $.get("'.Yii::$app->urlManager->createUrl('task-list/dropdown?id=').'"+$(this).val(), function(data) { 
     $("ul#tasksearch-subcategories_id").html(data); 
     })']
    ); ?>

    <?= $form->field($model, 'subcategories_id', ['template' => "
                                                <div class='dropdown'>
                                                    <button
                                                    class='btn btn-default dropdown-toggle'
                                                    data-toggle='dropdown'
                                                    type='button'>
                                                    <span>Подкатегории</span>
                                                    <span class='caret'></span>
                                                    </button>
                                                    {input}
                                                </div>"])->checkboxList([],
                                                ['tag' => 'ul',

                                                'class' => 'dropdown-menu',]); ?>
    <?php echo $form->field($model, 'min_price')->textInput(['style'=>'width:25%;']) ?>

        <div class="controls">
            <?php  echo $form->field($model, 'check_price')->checkbox(['value'   => 1]) ?>
            <?php  echo $form->field($model, 'check_time1')->checkbox(['value'   => (int)(time()-3600)]) ?>
            <?php  echo $form->field($model, 'check_time3')->checkbox(['value'   => (int)(time()-10800)]) ?>
            <?php  echo $form->field($model, 'check_time6')->checkbox(['value'   => (int)(time()-21600)]) ?>
            <?php  echo $form->field($model, 'check_time7dn')->checkbox(['value' => (int)(time()-604800)]) ?>
        </div>
    </div>
    <div  class="btn-group-sm">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-danger',"style"=>"margin: 5px; width:30%",'onclick'=>' 
     $.get("'.Yii::$app->urlManager->createUrl('task-list/dropdown?id=').'"+$("#cat").val(), function(data) { 
     $("ul#tasksearch-subcategories_id").html(data); 
     })' ]) ?>
        <?= Html::resetButton ('Сброс', ['class' => 'btn btn-default',"style"=>"margin: 5px; width:30% "]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $.get("dropdown?id="+$('#cat').val(), function(data) {
        $("ul#tasksearch-subcategories_id").html(data);
    })
</script>

