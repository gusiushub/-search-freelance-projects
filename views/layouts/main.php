<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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
    <div id="big-logo">
        <img src="../../web/img/2222.png">
        <!--		<img src="img/to.png">-->

    </div>
    <?php

//    NavBar::begin();
    ?>
    <div id="top-menu">
        <ul class="menu-list">
            <?php if (Yii::$app->user->isGuest) { ?>
                <li><a class="menu"  href="/site/index">Главния</a></li>
            <?php }else{ ?>
                <li><a class="menu" href="/task-list/index">Поиск</a></li>
                <li><a class="menu" href="/user/index">Личный кабинет</a></li>
            <?php } ?>
            <li><a class="menu" href="#">Новости</a></li>
            <li><a class="menu" href="#">FAQ</a></li>
            <?php if (Yii::$app->user->isGuest) { ?>
                <li><a class="menu" href="/site/signup">Регистрация</a></li>
                <li><a class="menu" href="/site/login">Авторизация</a></li>
            <?php } ?>
            <?php if (!Yii::$app->user->isGuest) { ?>
                <li><a class="menu" href="/user/logout">Выход</a></li>
            <?php } ?>
        </ul>
    </div>
    <?php
//    NavBar::end();
    ?>
</header>
<?php

NavBar::begin();

//    $menuItems = [
//        ['label' => 'Главная', 'url' => ['/site/index']],
////        ['label' => 'О нас', 'url' => ['/site/about']],
////        ['label' => 'Поиск', 'url' => ['/task-list/index']],
//    ];
//
//    if (Yii::$app->user->isGuest) {
//        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
//        $menuItems[] = ['label' => 'Авторизация', 'url' => ['/site/login']];
//    } else {
//        $menuItems = [
//            ['label' => 'Поиск', 'url' => ['/task-list/index']],
////            ['label' => 'О нас', 'url' => ['/site/about']],
//            ['label' => 'Личный кабинет', 'url' => ['/user/index']],
//
//        ];
//        $menuItems[] = '<li>'
//            . Html::beginForm(['/site/logout'], 'post')
//            . Html::submitButton(
//                'Выход (' . Yii::$app->user->identity->username . ')',
//                ['class' => 'btn btn-link logout']
//            )
//            . Html::endForm()
//            . '</li>';
//    }
//
//    echo Nav::widget([
//        'options' => ['class' => 'navbar-nav navbar-right'],
//        'items' => $menuItems,
//    ]);

NavBar::end();
?>
<div class="container">



    <div class="container">
<!--        --><?//= Breadcrumbs::widget([
//            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
//        ]) ?>
<!--        --><?//= Alert::widget() ?>
        <?= $content ?>
    </div>
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

