<?php

use app\models\Site;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<?php  $site = Site::find()->where('id = :site_id', [':site_id' => $model->site_id])->one(); ?>
<div class="row">
    <div class="col-xs-10">
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="../../web/img/<?php  echo $site['logo']; ?>" width="64px" height="60px" alt="...">
            </a>
            <div class="media-body">
                <h4 class="media-heading"><?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]).' ' ?></h4>
                <p><?= HtmlPurifier::process($model->text) ?></p>
                <p><a href="<?= HtmlPurifier::process($model->url) ?>"><?= HtmlPurifier::process($model->url) ?></a> </p>
            </div>
        </div>
    </div>
    <div class="col-xs-2">
        <?php if ($model->price==0){
            echo 'Договор';
        } else{?>
        <?= $model->price ?>р
        <?php } ?>
    </div>
    <div class="col-xs-10"></div>
    <div class="col-xs-2">
        <?= Html::a(Html::encode('Подробнее...'), ['view', 'id' => $model->id]).' ' ?>
    </div>
</div>
<hr>


