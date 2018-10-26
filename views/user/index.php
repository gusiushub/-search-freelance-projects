<?php

use app\models\User;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#">Профиль</a></li>
    <li><a href="/user/pay">Оплата</a></li>
<!--    <li><a href="#">Уведомления: </a></li>-->
</ul>

<?php if (User::isProfileComplete()!=1){ ?>
<div class="alert alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Заполните профиль!</strong> Чтобы пользоваться всем функционалом, вы должны <?php echo User::isProfileComplete(); ?>
</div>
<?php } ?>
<!--    <div class="container">-->
        <div style="margin-top: 25px" class="container">
            <div class="col-sm-6 col-md-4">
        <div class="panel panel-danger">

            <div class="panel-heading">
                <h2 class="panel-title">Информация о профиле: </h2>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin() ?>
                    <div class="input-group">
                        <span class="input-group-addon">Логин:<?= Yii::$app->user->identity->username ?></span>
                        <input type="hidden" class="form-control">
                    </div>
                    <hr>
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
                <?= Html::submitButton('Обновить', ['class'=>'btn btn-danger']) ?>
                <?php ActiveForm::end(); ?>
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
                        // console.log(res);
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
    <div >
    <h3>Прогресс бар</h3>

    <div class="progress progress-striped">
        <?php if (User::isProfileComplete()==1){ ?>
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
        <?php }elseif (User::isProfileComplete()=='заполнить E-mail и телефон!'){ ?>
            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
        <?php }elseif (User::isProfileComplete()=='заполнить телефон!'){ ?>
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
        <?php }elseif (User::isProfileComplete()=='заполнить E-mail!!'){ ?>
                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
        <?php } ?>
            <span class="sr-only">80% Complete (danger)</span>
        </div>
    </div>
    <hr>
    <h3>Уведомления: </h3><br>
    <?php if (User::isProfileComplete()!=1){ ?>
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
    <?php } ?>
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
    </div>

</div>

<!--<script type="text/javascript" src="https://auth.robokassa.ru/Merchant/PaymentForm/FormSS.js?MerchantLogin=waytowork&InvoiceID=0&Culture=ru&Encoding=utf-8&OutSum=1&SignatureValue=fea798e07fb286deecfbec5e00af361d"></script>-->


