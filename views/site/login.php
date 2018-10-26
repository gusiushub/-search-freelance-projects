<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';

?>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-offset-4 col-lg-4\">{label}<div >{input}</div><div class=\"col-lg-6\">{error}</div></div>",
            'labelOptions' => ['class' => ' control-label'],
            ],
    ]); ?>
    <h2 class="col-lg-offset-5"><?= Html::encode($this->title) ?></h2>
    <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'Логин'])->label('') ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Пароль'])->label('')?>
    <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-4 col-lg-2\">{input} {label}</div><div class=\"col-lg-2 \"> <a href='/site/request-password-reset'>Забыли пароль?</a></div><div class=\"col-lg-5\">{error}</div>",
        ]) ?>
    <div class="form-group">
        <div class="col-lg-offset-4 col-lg-6">
            <div class=" col-lg-3">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button','style'=>'width:100px']) ?>
            </div>
                <div class="col-lg-offset-3 col-lg-3">
            <?= \nodge\eauth\Widget::widget(['action' => 'site/login']) ?>
                </div>
        </div>
            <?php
            if (Yii::$app->getSession()->hasFlash('error')) {
                echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
            }
            ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>


