<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

    <ul class="nav nav-tabs">
        <li ><a href="/user/index">Профиль</a></li>
        <li class="active"><a href="/user/pay">Оплата</a></li>
        <!--    <li><a href="#">Уведомления: </a></li>-->
    </ul>
<br>
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin() ?>
        <section class="creditly-wrapper gray-theme">
            <h3>Credit Card</h3>
            <i>
                <div class="card-type" style="text-align:right;margin-top:10px;margin-right:10px;min-height:20px;margin-bottom:-15px"></div>
            </i>
            <div class="credit-card-wrapper">
                <div class="first-row form-group">
                    <div class="col-sm-8 controls">
                        <label class="control-label">Card Number</label>
                        <input class="number credit-card-number form-control"
                               type="text" name="number"
                               pattern="\d*"
                               inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
                               placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
                    </div>
                    <div class="col-sm-4 controls">
                        <label class="control-label">CVV</label>
                        <input class="security-code form-control"·
                               inputmode="numeric"
                               pattern="\d*"
                               type="text"
                               name="ccv"
                               placeholder="&#149;&#149;&#149;">
                    </div>
                </div>
                <div class="second-row form-group">
                    <div class="col-sm-8 controls">
                        <label class="control-label">Name on Card</label>
                        <input class="billing-address-name form-control"
                               type="text"
                               name="name"
                               placeholder="John Smith">
                    </div>
                    <div class="col-sm-4 controls">
                        <label class="control-label">Expiration</label>
                        <input class="expiration-month-and-year form-control"
                               type="text"
                               name="date"
                               placeholder="MM / YY">
                    </div>
                </div>
            </div>
        </section>

    </div>



<?php
//$js = <<<JS
//            $('form').on('beforeSubmit', function(){
//               var price = $('input[name="price"]').val(),
//
//                   data = {
//                    price
//                   };
//                $.ajax({
//                    url: '/user/pay',
//                    type: 'POST',
//                    data: data,
//                    success: function(res){
//                        // console.log(res);
//                        var html = "<br><br><br>" +
//                         " Сумма зачисления на ваш счет:  "+price +
//                         "<br>"+
//                         "<br>"+
//                         "<h2>Товарный чек</h2>"+
//                         "<table width='100%' border='4' bordercolor='black' style='border:2px'>" +
//                          "<thead bgcolor='yellow'><tr>" +
//                           "<th> № </th>" +
//                           "<th> Код транзакции </th>" +
//                           "<th> Сумма </th>" +
//                            "</tr></thead>" +
//                             "<tbody><tr>" +
//                              "<td>1</td>" +
//                              "<td>s234234234dfsdf</td>" +
//                              "<td>"+price+"</td>" +
//                               "</tr></tbody>" +
//                              "</table>"+
//                         "<form method='post' action='http://test.robokassa.ru/Index.aspx'><input type='hidden' name='MrchLogin' value='-- Ваш логин в системе --' /><input type='hidden' name='OutSum' value='-- Сумма платежа, разделитель дробной части - точка --' /><input type='hidden' name='InvId' value='-- Уникальный номер транзакции в Вашем магазине -- ' /><input type='hidden' name='Desc' value='-- Описание, например: покупка коньков -- ' /><input type='hidden' name='SignatureValue' value='{SIGNATURE}' /><br><input type='submit' class='btn btn-danger' value='Оплатить' /></form>";
//                        $(".content").html(html);
//                    },
//                    error: function(){
//                        alert('Error!');
//                    }
//                });
//                return false;
//            });
//JS;
//$this->registerJs($js);
//?>
<!---->
<!--<div class="content">-->
<!---->
<!--</div>-->



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