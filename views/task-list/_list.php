<?php

use app\models\Site;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<?php  $site = Site::find()->where('id = :site_id', [':site_id' => $model->site_id])->one(); ?>
        <div class="page">
        <div class="main-content">
        <div class="media">
            <a class="pull-left" href="#">
                <img style="margin-right: 5px" class="media-object" src="../../web/img/<?php  echo $site['logo']; ?>" width="60px" height="60px" alt="...">
            </a>
            <div class="media-body">
                <h4 class="media-heading">
                    <?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]).' ' ?>
                </h4>
                <p><?= HtmlPurifier::process($model->text) ?></p>
                <p>
                    <span class="media-left">
                        <?= Html::a(Html::encode('Подробнее...'), ['view', 'id' => $model->id]).' ' ?>
                    </span>
                    <span class="media-right">Оплата: <?php if ($model->price==0){
                            echo 'договор';
                        } else{?>
                            <?= $model->price ?>р
                        <?php } ?>
                    </span>
                </p>
<!--                    <p><a href="--><?//= HtmlPurifier::process($model->url) ?><!--">--><?//= HtmlPurifier::process($model->url) ?><!--</a> </p>-->
            </div>
        </div>
        </div>
        </div>
<hr>



