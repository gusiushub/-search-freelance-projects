<?php

/* @var $this yii\web\View */


use app\models\HomePage;
use app\models\Site;
use app\models\Сategories;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Главная';

?>
<div style="margin-bottom: 0; padding-bottom: 0;" class="col-lg-3 col-sm-3" >
    <?php $form = ActiveForm::begin([
        'action' => ['/site/login'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="control-group">
        <?= $form->field($model, 'site_id')
            ->dropDownList(ArrayHelper::map(Site::find()
                ->all(),'id','name'),['prompt'=>'Все сайты']) ?>

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
<div  class="col-lg-8 col-sm-8">
<?php for ($i=0;$i<9;$i++){ ?>
    <div class='media'>
        <a class='pull-right' href='/site/login'>
            <img    class='media-object' src='../../web/img/<?= HomePage::getImg($task[$i]['site_id']) ?>' width='100x' height='100px' alt='...'>
        </a>
        <div class='media-body'>
            <a href="/site/login"><h3 style=' font-weight: 600' class='media-heading'>

                <?= $task[$i]['title'] ?></h3></a><hr><?= $task[$i]['text'] ?>

            <br><br>
            <p class='media-left' style='font-size: large'>
                <?php if ($task[$i]['price']==0 or $task[$i]['price']==1){
                    echo 'Цена: договор';
                } else{?>
                    Цена:<b> <?= $task[$i]['price'] ?></b>р
                <?php } ?>

            </p>
            <p style='text-align: right' class='media-right'>
                <a href='<?= $task[$i]['url'] ?>'>Перейти на сайт с объявлением</a>
            </p>
        </div>
    </div>
    <br>
<?php } ?>
</div>
