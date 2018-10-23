<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
    <ul class="nav nav-tabs">
        <li ><a href="/user/index">Профиль</a></li>
        <li class="active"><a href="/user/pay">Оплата</a></li>
        <!--    <li><a href="#">Уведомления: </a></li>-->
    </ul>
<?php $form = ActiveForm::begin() ?>
Укажите сумму пополнения баланса для формирования чека
    <div class="input-group">
    <input name="price" type="text" class="form-control" value="0">
    </div>
<?= Html::submitButton('Сформировать', ['class'=>'btn btn-danger']) ?>
<?php ActiveForm::end(); ?>
<?php
$js = <<<JS
            $('form').on('beforeSubmit', function(){
               var price = $('input[name="price"]').val(),
                  
                   data = {
                    price         
                   };
                $.ajax({
                    url: '/user/pay',
                    type: 'POST',
                    data: data,
                    success: function(res){
                        // console.log(res);
                        var html = "<br><br><br>" +
                         " Сумма зачисления на ваш счет:  "+price +
                         "<br>"+
                         "<br>"+
                         "<h2>Товарный чек</h2>"+
                         "<table width='100%' border='4' bordercolor='black' style='border:2px'>" +
                          "<thead bgcolor='yellow'><tr>" +
                           "<th> № </th>" +
                           "<th> Код транзакции </th>" +
                           "<th> Сумма </th>" +
                            "</tr></thead>" +
                             "<tbody><tr>" +
                              "<td>1</td>" +
                              "<td>s234234234dfsdf</td>" +
                              "<td>"+price+"</td>" +
                               "</tr></tbody>" +
                              "</table>"+
                         "<form method='post' action='http://test.robokassa.ru/Index.aspx'><input type='hidden' name='MrchLogin' value='-- Ваш логин в системе --' /><input type='hidden' name='OutSum' value='-- Сумма платежа, разделитель дробной части - точка --' /><input type='hidden' name='InvId' value='-- Уникальный номер транзакции в Вашем магазине -- ' /><input type='hidden' name='Desc' value='-- Описание, например: покупка коньков -- ' /><input type='hidden' name='SignatureValue' value='{SIGNATURE}' /><br><input type='submit' class='btn btn-danger' value='Оплатить' /></form>";
                        $(".content").html(html);
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

<div class="content">

</div>



<?php
//if ($_POST['price']){
//?>
<!--<form method="post" action="http://test.robokassa.ru/Index.aspx">-->
<!--<input type="hidden" name="MrchLogin" value="-- Ваш логин в системе --" />-->
<!--<input type="hidden" name="OutSum" value="-- Сумма платежа, разделитель дробной части - точка --" />-->
<!--<input type="hidden" name="InvId" value="-- Уникальный номер транзакции в Вашем магазине -- " />-->
<!--<input type="hidden" name="Desc" value="-- Описание, например: покупка коньков -- " />-->
<!--<input type="hidden" name="SignatureValue" value="{SIGNATURE}" />-->
<!--<input type="submit" value="Оплатить" />-->
<!---->
<!--</form>-->
<?php
//}
//?>