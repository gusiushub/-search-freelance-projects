<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<ul class="nav nav-tabs">
<li class="active"><a href="#">Профиль</a></li>
<li><a href="#">Настройки</a></li>
<li><a href="#">Уведомления: </a></li>
</ul>

<div style="margin-top: 30px" class="row">
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-danger">

            <div class="panel-heading">
                <h2 class="panel-title">Информация о профиле: </h2>
            </div>
            <div class="panel-body">
<!--                <form id="form" method="post">-->
                <?php $form = ActiveForm::begin() ?>
                    <div class="input-group">
                        <span class="input-group-addon">Имя: </span>
                        <input name="f_name" type="text" class="form-control" value="<?= Yii::$app->user->identity->f_name ?>">
                    </div>
                    <hr>
                    <div class="input-group">
                        <span class="input-group-addon">Фамилия: </span>
                        <input name="s_name" type="text" class="form-control" value="<?= Yii::$app->user->identity->s_name ?>">
                    </div>
                    <hr>
                    <div class="input-group">
                        <span class="input-group-addon">Логин: </span>
                        <input name="login" type="text" class="form-control" value="<?= Yii::$app->user->identity->username ?>">
                    </div>
                    <hr>
                    <div class="input-group">
                        <span class="input-group-addon"> E-mail: </span>
                        <input name="email" type="text" class="form-control" value="<?= Yii::$app->user->identity->email ?>">
                    </div>
                    <hr>
                    <div class="input-group">
                        <span class="input-group-addon">Моб. телефон: </span>
                        <input name="phone" type="text" class="form-control" value="<?= Yii::$app->user->identity->phone ?>">
                    </div>
                    <hr>
                    <div class="input-group">
                        <span class="input-group-addon">Тариф: </span>
                        <input type="text" class="form-control" value="<?= Yii::$app->user->identity->tariff ?>">
                    </div>
                    <hr>
<!--                    <input name="_csrf" type="hidden" value="--><?//=Yii::$app->request->getCsrfToken()?><!--">-->
                <?= Html::submitButton('Обновить', ['class'=>'btn btn-danger']) ?>
<!--                    <input name="submit" type="button" id="submittt" class="btn btn-danger" value="Обновить">-->
                <?php ActiveForm::end(); ?>
<!--                </form>-->

                <?php
                $js = <<<JS
            $('form').on('beforeSubmit', function(){
               var f_name = $('input[name="f_name"]').val(),
                   s_name = $('input[name="s_name"]').val(),
                   login = $('input[name="login"]').val(),
                   email = $('input[name="email"]').val(),
                   phone = $('input[name="phone"]').val(),
                   data = {
                    f_name,
                    s_name,
                    login,
                    email,
                    phone
                   };
              // var jsdata = JSON.stringify(data);
                $.ajax({
                    url: '/ajax/setting',
                    type: 'POST',
                    data: data,
                    success: function(res){
                        console.log(res);
                    },
                    error: function(){
                        alert('Error!');
                    }
                });
                return false;
            });
JS;

                $this->registerJs($js);
                ?>
            </div>

        </div>
    </div>
    <h3>Прогресс бар</h3>
    <div class="progress progress-striped">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
            <span class="sr-only">80% Complete (danger)</span>
        </div>
    </div>
    <h3>Уведомления: </h3><br>
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <img  src="../../web/img/vnimanie.jpg" alt="...">
            <div class="caption">
                <h3>Заполните профиль</h3>
                <p>Для того, чтобы все функционировало, требуется ПОЛНОСТЬЮ заполнить свой профиль. После того как вы все заполните, вы сможете оплатить ваш тарифный план.</p>
                <p><a href="#" class="btn btn-danger" role="button">Подключить</a></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <img src="../../web/img/tarif.png" alt="...">
            <div class="caption">
                <h3>Тариф</h3>
                <p>Подключите тариф *** для безлимитного доступа к поиску заказов</p>
                <p><a href="#" class="btn btn-danger" role="button">Подключить</a> <a href="#" class="btn btn-default" role="button">Подробнее</a></p>
            </div>
        </div>
    </div>

</div>
<script>
    // alert('sdads');
    // $('#submittt').click(function() {
    //
    //     var name = $('input[name="f_name"]').val(),
    //         _csrf = $('input[name="_csrf"]').val(),
    //         data = {
    //             name
    //         };
    //     var jsdata = JSON.stringify(data);
    //     $.ajax({
    //         type: "POST",
    //         url: "/ajax/setting",
    //         data: {
    //             profile_seller7: jsdata,
    //             _csrf: _csrf
    //         }
    //     });
    // }
</script>


