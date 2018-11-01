<?php

use app\models\Site;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$site = Site::find()->where('id = :site_id', [':site_id' => $model->site_id])->one(); ?>
<hr border="3px solid grey" color="grey">
<p>
    <div class="media">
        <h4 class="media-heading">
                <?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]).' ' ?>
        </h4>
        <a class="pull-left" href="#">
            <img align="left" width="70" vspace="5" hspace="5" style="margin-right: 5px" class="media-object" src="../../web/img/<?php  echo $site['logo']; ?>" width="60px" height="60px" alt="...">
        </a>
        <p style='max-height: 60px; overflow: hidden; text-overflow: ellipsis" white-space: nowrap;'><?= HtmlPurifier::process($model->text) ?></p>

        <span class="media-left">
            <?= Html::a(Html::encode('Подробнее...'), ['view', 'id' => $model->id]).' ' ?>
        </span>
        <span class="media-right">Оплата:
            <b><?php if ($model->price==0 or $model->price==1){
                echo 'договор';
            } else{?>
                <?= $model->price ?>р
            <?php } ?>
            </b>
        </span>
<!--                    <p><a href="--><?//= HtmlPurifier::process($model->url) ?><!--">--><?//= HtmlPurifier::process($model->url) ?><!--</a> </p>-->
    </div>

</p>




