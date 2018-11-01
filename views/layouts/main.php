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
    <div class="col-sm-12"  >
    <div class="col-sm-offset-3 col-sm-6"  >
        <img id="big-logo" class="img-responsive"   src="../../web/img/2222.png">
        <!--		<img src="img/to.png">-->

    </div>
    <?php

//    NavBar::begin();
    ?>
    <div class="col-sm-6"  id="top-menu">
<!--        <ul class="menu-list">-->
<!--            --><?php //if (Yii::$app->user->isGuest) { ?>
<!--                <li><a class="menu"  href="/site/index">Главния</a></li>-->
<!--            --><?php //}else{ ?>
<!--                <li><a class="menu" href="/task-list/index">Поиск</a></li>-->
<!--                <li><a class="menu" href="/user/index">Личный кабинет</a></li>-->
<!--            --><?php //} ?>
<!--            <li><a class="menu" href="#">Новости</a></li>-->
<!--            <li><a class="menu" href="#">FAQ</a></li>-->
<!--            --><?php //if (Yii::$app->user->isGuest) { ?>
<!--                <li><a class="menu" href="/site/signup">Регистрация</a></li>-->
<!--                <li><a class="menu" href="/site/login">Авторизация</a></li>-->
<!--            --><?php //} ?>
<!--            --><?php //if (!Yii::$app->user->isGuest) { ?>
<!--                <li><a class="menu" href="/user/logout">Выход</a></li>-->
<!--            --><?php //} ?>
<!--        </ul>-->
<!--    </div>-->
    <?php
//    NavBar::end();
    ?>

<?php
echo Nav::widget([
    'items' => [
        [
            'label' => 'Home',
            'url' => ['/site/index'],
            'linkOptions' => [],
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
        ],
        [
            'label' => 'Авторизация',
            'url' => ['/site/login'],
            'linkOptions' => [],
        ],
        [
            'label' => 'Dropdown',
            'items' => [
                 ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                 '<div class="dropdown-divider"></div>',
                 '<div class="dropdown-header">Dropdown Header</div>',
                 ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
            ],
        ],
        [
            'label' => 'Login',
            'url' => ['site/login'],
            'visible' => Yii::$app->user->isGuest
        ],
    ],
    'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
]);


    //NavBar::begin([
    //            'options' => [
    //                'class' => 'navbar navbar-default navbar-static-top',
    //            ],
    //        ]);
    //?>
    </div>
    </div>
<!--<div id="custom-bootstrap-menu" class="navbar navbar-default navbar-fixed-top" role="navigation">-->
<!--    <div class="container-fluid">-->
<!--        <div class="navbar-header"><a class="navbar-brand" href="#">Brand</a>-->
<!--            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>-->
<!--            </button>-->
<!--        </div>-->
<!--        <div class="collapse navbar-collapse navbar-menubuilder">-->
<!--            <ul class="nav navbar-nav navbar-right">-->
<!--                <li><a href="/">Home</a>-->
<!--                </li>-->
<!--                <li><a href="/products">Products</a>-->
<!--                </li>-->
<!--                <li><a href="/about-us">About Us</a>-->
<!--                </li>-->
<!--                <li><a href="/contact">Contact Us</a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<?php
?>

<!--<div id="custom-bootstrap-menu" class="navbar navbar-default navbar-static-top" role="navigation">-->

<!--    <div class="container-fluid">-->

<!--        <div class="collapse navbar-collapse navbar-menubuilder">-->
            <?php
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
//        'options' => ['class' => 'nav navbar-nav navbar-right'],
//        'items' => $menuItems,
//    ]);
//    ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<?php
//NavBar::end();
//?>
    </div>
</header>
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

