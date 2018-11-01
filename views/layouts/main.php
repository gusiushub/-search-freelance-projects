<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <meta name="generator" content="2018.0.0.379"/>-->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>-->
    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<!--    <link rel="stylesheet" type="text/css" href="../../web/css/site_global.css?crc=444006867"/>-->
<!--    <link rel="stylesheet" type="text/css" href="../../web/css/index.css?crc=3860585067" id="pagesheet"/>-->
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <div class="col-sm-12"  >
    <div class="col-sm-offset-3 col-sm-6"  >
        <img id="big-logo" class="img-responsive"   src="../../web/img/2222.png">
    </div>
</header>
<?php
NavBar::begin([
    'options' => [
//            'style'=>[
//            'width:100%; margin:0 ; padding:0;'],navbar navbar-inverse navbar-fixed-top
        'class' => 'navbar navbar-inverse navbar-static-top',
    ],
]);
echo Nav::widget([
    'items' => [
        [
            'label' => 'Главная',
            'url' => ['/site/index'],
            'linkOptions' => [],
            'visible' => Yii::$app->user->isGuest
        ],

        [
            'label' => 'Поиск',
            'url' => ['/task-list/index'],
            'linkOptions' => [],
            'visible' => !Yii::$app->user->isGuest
        ],

        [
            'label' => 'Личный кабинет',
            'url' => ['/user/index'],
            'linkOptions' => [],
            'visible' => !Yii::$app->user->isGuest
        ],

        [
            'label' => 'Новости',
            'url' => ['/site/news'],
            'linkOptions' => [],
        ],
        [
            'label' => 'FAQ',
            'url' => ['/site/faq'],
            'linkOptions' => [],
        ],
        [
            'label' => 'Регистрация',
            'url' => ['/site/signup'],
            'linkOptions' => [],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => 'Авторизация',
            'url' => ['/site/login'],
            'linkOptions' => [],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            "label" => "Выход (". Yii::$app->user->identity->username .")",
            'url' => ['/user/logout'],
            'linkOptions' => [],
            'visible' => !Yii::$app->user->isGuest
        ],

    ],
    'options' => ['class' =>'nav navbar-nav navbar-right'], // set this to nav-tab to get tab-styled navigation
]);

NavBar::end();
?>

    <div class="container">

        <?= $content ?>

    </div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy;Way to Work <?= date('Y') ?></p>

        <p class="pull-right"><?php echo \Yii::t('yii', 'By {yii}', [
                'yii' => '<a href="http://www.yiiframework.com/" rel="external">' . \Yii::t('yii',
                        'gusiushub') . '</a>',
            ]); ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

