<?php

/* @var $this yii\web\View */

use yii\helpers\VarDumper;

$this->title = 'My Yii Application';
$identity = Yii::$app->getUser()->getIdentity();
if (isset($identity->profile)) {
    $user_vk = $identity->profile;
    $name_vk = explode(' ',$user_vk['name']);
     var_dump($name_vk);
    VarDumper::dump($identity->profile, 10, true);
}
?>
