<?php

namespace app\models;

use yii\base\Model;

class PayModel extends Model
{
    public static function Action()
    {
        if(!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"])  && !empty($_POST["price"])){ // если был пост

            $name = trim(htmlspecialchars(strip_tags(base64_encode(urlencode($_POST["name"]))))); // принимаем параметры с формы
            $email = trim(htmlspecialchars(strip_tags(base64_encode(urlencode($_POST["email"]))))); // принимаем параметры с формы
            $phone = trim(htmlspecialchars(strip_tags(base64_encode(urlencode($_POST["phone"]))))); // принимаем параметры с формы

            $mrh_login = "ident"; // идентификатор магазина
            $mrh_pass1 = "password-1"; // пароль #1

            $inv_id = file_get_contents("count.txt"); //получаем номер заказа с файла count.txt

            $inv_desc = "Тестовая оплата"; // описание заказа

            $out_summ = "100"; // сумма

            $shp_item = 1; // тип товара

            $in_curr = ""; // предлагаемая валюта платежа

            $culture = "ru"; // язык

            $encoding = "utf-8"; // кодировка

            $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item:shp_mulo=$email:shp_names=$name:shp_phone=$phone"); // формирование подписи

            // Перенаправляем пользователя на страницу оплаты
            Header("Location: http://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
                "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
                "&Culture=$culture&Encoding=$encoding&shp_mulo=$email&shp_names=$name&shp_phone=$phone");

        }
    }

    /**
     *
     */
    public static function Result()
    {
        $mrh_pass2 = "password-2"; // пароль #2

        // чтение параметров
        $out_summ = $_REQUEST["OutSum"]; // по умолчанию (не трогать)
        $inv_id = $_REQUEST["InvId"]; // по умолчанию (не трогать)
        $shp_item = $_REQUEST["Shp_item"]; // по умолчанию (не трогать)
        $crc = $_REQUEST["SignatureValue"]; // по умолчанию (не трогать)

        $shp_mulo = $_REQUEST["shp_mulo"]; // принимаем дополнительный параметр
        $shp_names = $_REQUEST["shp_names"]; // принимаем дополнительный параметр
        $shp_phone = $_REQUEST["shp_phone"]; // принимаем дополнительный параметр

        $crc = strtoupper($crc); // переводим ключ в верхний регистр

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item:shp_mulo=$shp_mulo:shp_names=$shp_names:shp_phone=$shp_phone")); // формируем новый ключ

        if ($my_crc != $crc) // проверка корректности подписи
        {
            echo "bad sign\n";
            exit(); // останавливаем выполнение скрипта, если подписи не совпадают
        }

        $count = file_get_contents("count.txt"); //получаем номер покупки

        $fp = fopen('count.txt', 'w'); //перезаписываем номер покупки в файл count.txt
        fwrite($fp, $count+1);
        fclose($fp);

        // конвертируем полученные данные в нормальный режим
        $email_k = urldecode(base64_decode($shp_mulo));
        $name_k = urldecode(base64_decode($shp_names));
        $phone_k = urldecode(base64_decode($shp_phone));

        $result = $email_k."\r\n".$name_k."\r\n".$phone_k; // поместим данные в одну переменную

        // записываем информацию о последней покупке с сайта в файл last_order.txt
        // конечно лучше заносить данные в базу, но я показал простой вариант
        $fp = fopen('last_order.txt', 'w');
        fwrite($fp, $result);
        fclose($fp);

        echo "OK$inv_id\n"; // признак успешно проведенной операции (обязательно!)
    }

    /**
     *
     */
    public static function Success()
    {
        $mrh_pass1 = "password-1"; // пароль #1

        // чтение параметров
        $out_summ = $_REQUEST["OutSum"]; // по умолчанию (не трогать)
        $inv_id = $_REQUEST["InvId"]; // по умолчанию (не трогать)
        $shp_item = $_REQUEST["Shp_item"]; // по умолчанию (не трогать)
        $crc = $_REQUEST["SignatureValue"]; // по умолчанию (не трогать)

        $shp_mulo = $_REQUEST["shp_mulo"]; // если нужно принимаем дополнительный параметр
        $shp_names = $_REQUEST["shp_names"]; // если нужно принимаем дополнительный параметр
        $shp_phone = $_REQUEST["shp_phone"]; // если нужно принимаем дополнительный параметр

        $crc = strtoupper($crc); // переводим ключ в верхний регистр

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item:shp_mulo=$shp_mulo:shp_names=$shp_names:shp_phone=$shp_phone")); // формируем новый ключ

        if ($my_crc != $crc) // проверка корректности подписи
        {
            echo "bad sign\n";
            exit(); // останавливаем выполнение скрипта, если подписи не совпадают
        }

        // если всё прошло гладко, то переводим пользователя на любую страницу Вашего сайта,
        // например я перевожу на главную и передаю GET параметр order со значением ok
        // а на главной странице проверяю, если значение order = ok, то выдаю ему модальное окошко (спасибо за оплату)
        header("Location:/?order=ok");
    }

    /**
     * Вызывать при отказе от оплаты или ошибки
     */
    public static function Fail()
    {
        $inv_id = $_REQUEST["InvId"];
        echo "Вы отказались от оплаты. Заказ# $inv_id\n";
        echo "You have refused payment. Order# $inv_id\n";
    }
}