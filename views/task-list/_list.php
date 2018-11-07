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
            <img align="left"  vspace="5" hspace="5" style="margin-right: 5px" class="media-object" src="../../web/img/<?php  echo $site['logo']; ?>" width="64px" height="64px" alt="...">
        </a>
        <p style='max-height: 60px; overflow: hidden; text-overflow: ellipsis" white-space: nowrap;'>
        	<?php echo mb_strimwidth($model->text, 0, 500, "..."); ?>
        	</p>
<!--        <p style='max-height: 60px; overflow: hidden; text-overflow: ellipsis" white-space: nowrap;'>--><?//= HtmlPurifier::process(trim($model->text)) ?><!--</p>-->

        <span class="media-left col-lg-3 col-sm-3">
            <?= Html::a(Html::encode('Подробнее...'), ['view', 'id' => $model->id]).' ' ?>
        </span>
        <span class="col-lg-4 col-sm-4">
            <?php
            echo('Дата:  '. $model->date .'  Время: '. date("H:i",$model->time_unix));
            ?>
        </span>
        <span class="media-right col-lg-3 col-sm-3">Оплата:
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




