<?php
namespace app\components\helpers;
use Yii,
    yii\helpers\Html,
    yii\helpers\BaseArrayHelper,
    DateTime;
/**
 * Class FunctionHelper
 * @package app\components\helpers
 */
class FunctionHelper
{
    const DIR_ICO_LANGS = '/img/ico-langs/';
    const EXTENSION_ICO_LANGS = '.png';
    /**
     * @param $ot
     * @param $do
     * @param $text
     * @return mixed
     */
    public static function resOtDo($ot, $do, $text)
    {
        if ($ot != '') {
            $poz = strpos(strtoupper($text), strtoupper($ot)) + strlen($ot);
            $text = substr($text, $poz);
        }
        $var = substr($text, 0, strpos(strtoupper($text), strtoupper($do)));
        $text = substr($text, strlen($var) + strlen($do));
        $result[0] = $var;
        $result[1] = $text;
        return $result;
    }
    /**
     * @return array
     */
    public static function getListLetters()
    {
        return [
            '0' => 'Вся страна',
            '1' => 'А',
            '2' => 'Б',
            '3' => 'В',
            '4' => 'Г',
            '5' => 'Д',
            '6' => 'Е',
            '7' => 'Ё',
            '8' => 'Ж',
            '9' => 'З',
            '10' => 'И',
            '11' => 'Й',
            '12' => 'К',
            '13' => 'Л',
            '14' => 'М',
            '15' => 'Н',
            '16' => 'О',
            '17' => 'П',
            '18' => 'Р',
            '19' => 'С',
            '20' => 'Т',
            '21' => 'У',
            '22' => 'Ф',
            '23' => 'Х',
            '24' => 'Ц',
            '25' => 'Ч',
            '26' => 'Ш',
            '27' => 'Щ',
            '28' => 'Э',
            '29' => 'Ю',
            '30' => 'Я',
        ];
    }
    /**
     * @param $format
     * @param $date
     * @param bool $hmi
     * @return array|mixed
     */
    private static function dateFor($format, $date, $hmi = false)
    {
        if (!is_array($date)) {
            $date = [$date];
        }
        $dates = [];
        foreach ($date as $d) {
            $dates[] = !empty($d) ? date($format . ($hmi ? " H:i" : ""), strtotime($d)) : null;
        }
        return count($dates) > 1 ? $dates : $dates[0];
    }
    /**
     * @return array
     */
    public static function getPaymentMethodList()
    {
        return [
            '0' => 'Онлайн банковской картой',
            '1' => 'Онлайн банковской картой',
            '2' => 'Оплата через банк (для частных лиц)',
            '3' => 'Оплата наличными',
            '4' => 'Онлайн касса',
            '5' => 'Электронные кошельки Яндекс. Деньги',
            '6' => 'Электронные кошельки Qiwi',
            '7' => 'Электронные кошельки Webmoney',
            '8' => 'Безналичный расчёт (для организаций)',
        ];
    }
    /**
     * @param array|string $date
     * @param bool $hmi
     * @return array|mixed
     */
    public static function dateForMysql($date, $hmi = false)
    {
        return empty($date) ? null : self::dateFor("Y-m-d", $date, $hmi);
    }
    /**
     * @param array|string $date
     * @param bool $hmi
     * @return array|mixed
     */
    public static function dateForForm($date, $hmi = false)
    {
        return ($date == '0000-00-00' || empty($date)) ? null : self::dateFor("d.m.Y", $date, $hmi);
    }
    /**
     * @param array|string $date
     * @param bool $hmi
     * @return array|mixed
     */
    public static function dateForFormOrToday($date, $hmi = false)
    {
        return ($date == '0000-00-00' || empty($date)) ? date('d.m.Y') : self::dateFor("d.m.Y", $date, $hmi);
    }
    /**
     * @param $date
     * @return string
     */
    public static function mysqlDate($date, $hmi = false)
    {
        $dateNewFormat = "";
        if (!empty($date) && $date != '0000-00-00') {
            $dateFormat = DateTime::createFromFormat("Y-m-d" . ($hmi ? " H:i:s" : ""), $date);
            $dateNewFormat = $dateFormat->format("d.m.Y" . ($hmi ? " H:i:s" : ""));
        }
        return $dateNewFormat;
    }
    /**
     * @param $date
     * @param bool $hmi
     * @return string
     */
    public static function mysqlDateTemplate($date, $hmi = false)
    {
        $dateNewFormat = "";
        if (!empty($date) && $date != '0000-00-00') {
            $dateFormat = DateTime::createFromFormat("Y-m-d" . ($hmi ? " H:i:s" : ""), $date);
            $dateNewFormat = $dateFormat->format("Y/m/d" . ($hmi ? " H:i:s" : ""));
        }
        return $dateNewFormat;
    }
    /**
     * @param $date
     * @param bool $hmi
     * @return string
     */
    public static function dateMysqlNQ($date, $hmi = false)
    {
        $dateNewFormat = null;
        if (!empty($date)) {
            $dateFormat = DateTime::createFromFormat("d.m.Y" . ($hmi ? " H:i:s" : ""), $date);
            $dateNewFormat = $dateFormat->format("Y-m-d" . ($hmi ? " H:i:s" : ""));
        }
        return $dateNewFormat;
    }
    /**
     * Склонения слов после числительных
     * @param $number
     * @param array $after
     * @return string
     */
    public static function declOfNum($number, $after)
    {
        $cases = [2, 0, 1, 1, 1, 2];
        return $number . ' ' . $after[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
    /**
     * 'out' => [
     *     ['id' => '<prod-id-1>', 'name' => '<prod-name1>'],
     *     ['id' => '<prod_id_2>', 'name' => '<prod-name2>']
     * ],
     * @param $arr
     * @param $attrId
     * @param $attrName
     * @return array
     */
    public static function mapToIdName($arr, $attrId, $attrName)
    {
        $map = BaseArrayHelper::map($arr, $attrId, $attrName);
        $result = [];
        foreach ($map as $id => $name) {
            $result[] = ['id' => $id, 'name' => $name];
        }
        return $result;
    }
    /**
     * @param $format
     * @param $date
     * @return bool|null|string
     */
    public static function dateToFormat($format, $date)
    {
        if (!empty($date)) {
            $dateUnix = strtotime($date);
            if ($dateUnix > 0) {
                return date($format, $dateUnix);
            }
            return null;
        }
        return $date;
    }
    /**
     * @param $date1
     * @param int $date2
     * @return int|string
     */
    public static function getDateDiff($date1, $date2 = 0)
    {
        $dateObj = new DateTime($date1);
        $now = !empty($date2) ? new DateTime($date2) : new DateTime();
        $interval = $dateObj->diff($now);
        if ($dateObj > $now) {
            return $interval->format('%a');
        }
        return 0;
    }
    /**
     * @param $date
     * @param $days
     * @return DateTime
     */
    public static function addDays($date, $days)
    {
        $date = new DateTime($date);
        $date->add(new \DateInterval('P' . intval($days) . 'D'));
        return $date;
    }
    /**
     * @param int $length
     * @return string
     */
    public static function generateString($length = 40)
    {
        $chars = 'ABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
    /**
     * @param $amount
     * @param $curr
     * @param $date
     * @return array
     */
    public static function getUSD($amount, $curr, $date)
    {
        $result = [];
        $now = @simplexml_load_file('http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in ("USDEUR","USDAED","USDGBP","USDRUB")&env=store://datatables.org/alltableswithkeys');
        if (isset($now->results->rate)) {
            foreach ($now->results->rate as $cur) {
                $bid = ((float)$cur->Bid - ((float)$cur->Bid / 100 * 3));
                $ask = (float)$cur->Ask + ((float)$cur->Bid / 100 * 3);
                if (!empty($cur->Date)) {
                    $datetime = new DateTime($cur->Date);
                    $date = $datetime->format('d.m.Y');
                } else {
                    $date = '';
                }
                switch ($cur->Name) {
                    case 'USD/PLN':
                        $result['USD/PLN'] = ['value' => round($bid, 2), 'data' => [$date, 'USD', 'Zł']];
                        break;
                    case 'EUR/PLN':
                        $result['EUR/PLN'] = ['value' => round($bid, 2), 'data' => [$date, 'EUR', 'Zł']];
                        break;
                    case 'PLN/UAH':
                        $result['PLN/UAH'] = ['value' => round($ask, 2), 'data' => [$date, 'Zł', 'UAH']];
                        break;
                    case 'PLN/RUB':
                        $result['PLN/RUB'] = ['value' => round($ask, 2), 'data' => [$date, 'Zł', 'RUB']];
                        break;
                }
            }
        }
        return $result;
    }
    /**
     * @param $text
     * @return string
     */
    public static function translit($text)
    {
        $dictionary = array(
            'й' => 'i',
            'ц' => 'c',
            'у' => 'u',
            'к' => 'k',
            'е' => 'e',
            'н' => 'n',
            'г' => 'g',
            'ш' => 'sh',
            'щ' => 'shch',
            'з' => 'z',
            'х' => 'h',
            'ъ' => '',
            'ф' => 'f',
            'ы' => 'y',
            'в' => 'v',
            'а' => 'a',
            'п' => 'p',
            'р' => 'r',
            'о' => 'o',
            'л' => 'l',
            'д' => 'd',
            'ж' => 'zh',
            'э' => 'e',
            'ё' => 'e',
            'я' => 'ya',
            'ч' => 'ch',
            'с' => 's',
            'м' => 'm',
            'и' => 'i',
            'т' => 't',
            'ь' => '',
            'б' => 'b',
            'ю' => 'yu',
            'Й' => 'I',
            'Ц' => 'C',
            'У' => 'U',
            'К' => 'K',
            'Е' => 'E',
            'Н' => 'N',
            'Г' => 'G',
            'Ш' => 'SH',
            'Щ' => 'SHCH',
            'З' => 'Z',
            'Х' => 'X',
            'Ъ' => '',
            'Ф' => 'F',
            'Ы' => 'Y',
            'В' => 'V',
            'А' => 'A',
            'П' => 'P',
            'Р' => 'R',
            'О' => 'O',
            'Л' => 'L',
            'Д' => 'D',
            'Ж' => 'ZH',
            'Э' => 'E',
            'Ё' => 'E',
            'Я' => 'YA',
            'Ч' => 'CH',
            'С' => 'S',
            'М' => 'M',
            'И' => 'I',
            'Т' => 'T',
            'Ь' => '',
            'Б' => 'B',
            'Ю' => 'YU',
            '\-' => '-',
            '\s' => '-',
            '[^a-zA-Z0-9\-]' => '',
            '[-]{2,}' => '-',
        );
    }
    /**
     * @param $text
     * @param bool $toLowCase
     * @return string
     */
    public static function cyrillicToLatin($text, $toLowCase = false)
    {
        $text = htmlentities($text, null, 'utf-8');
        $text = str_replace("&nbsp;", "", $text);
        $text = html_entity_decode($text);
        $matrix = array(
            "й" => "i", "ц" => "c", "у" => "u", "к" => "k", "е" => "e", "н" => "n",
            "г" => "g", "ш" => "sh", "щ" => "shch", "з" => "z", "х" => "h", "ъ" => "",
            "ф" => "f", "ы" => "y", "в" => "v", "а" => "a", "п" => "p", "р" => "r",
            "о" => "o", "л" => "l", "д" => "d", "ж" => "zh", "э" => "e", "ё" => "e",
            "я" => "ya", "ч" => "ch", "с" => "s", "м" => "m", "и" => "i", "т" => "t",
            "ь" => "", "б" => "b", "ю" => "yu",
            "Й" => "I", "Ц" => "C", "У" => "U", "К" => "K", "Е" => "E", "Н" => "N",
            "Г" => "G", "Ш" => "SH", "Щ" => "SHCH", "З" => "Z", "Х" => "X", "Ъ" => "",
            "Ф" => "F", "Ы" => "Y", "В" => "V", "А" => "A", "П" => "P", "Р" => "R",
            "О" => "O", "Л" => "L", "Д" => "D", "Ж" => "ZH", "Э" => "E", "Ё" => "E",
            "Я" => "YA", "Ч" => "CH", "С" => "S", "М" => "M", "И" => "I", "Т" => "T",
            "Ь" => "", "Б" => "B", "Ю" => "YU",
            "«" => "", "»" => "", " " => "-", "+" => "","=" => "", '\&' => "",
            "(" => "", ")" => "", "?" => "", "!" => "", ":" => "", ";" => "",
            "#" => "", "№" => "", "/" => "-", "  " => "",  " " => "-",
            "&nbsp;" => "", "\n" => "", "%" => "", "\"" => "",
            '\.' => '',   '.' => '', ',' => '',
        );
        // Enforce the maximum component length
        $maxlength = 100;
        $text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($text))), $maxlength, '<br>', false)), 0, 1));
        //$text = substr(, 0, $maxlength);
        foreach ($matrix as $from => $to) {
            $text = str_replace($from, $to, $text);
        }
// Optionally convert to lower case.
        if ($toLowCase) {
            $text = mb_strtolower($text);
        }
        return $text;
    }
    public static function lightCyrillicToLatin($text, $toLowCase = false)
    {
        $matrix = array(
            "й" => "i", "ц" => "c", "у" => "u", "к" => "k", "е" => "e", "н" => "n",
            "г" => "g", "ш" => "sh", "щ" => "shch", "з" => "z", "х" => "h", "ъ" => "",
            "ф" => "f", "ы" => "y", "в" => "v", "а" => "a", "п" => "p", "р" => "r",
            "о" => "o", "л" => "l", "д" => "d", "ж" => "zh", "э" => "e", "ё" => "e",
            "я" => "ya", "ч" => "ch", "с" => "s", "м" => "m", "и" => "i", "т" => "t",
            "ь" => "", "б" => "b", "ю" => "yu",
            "Й" => "I", "Ц" => "C", "У" => "U", "К" => "K", "Е" => "E", "Н" => "N",
            "Г" => "G", "Ш" => "SH", "Щ" => "SHCH", "З" => "Z", "Х" => "X", "Ъ" => "",
            "Ф" => "F", "Ы" => "Y", "В" => "V", "А" => "A", "П" => "P", "Р" => "R",
            "О" => "O", "Л" => "L", "Д" => "D", "Ж" => "ZH", "Э" => "E", "Ё" => "E",
            "Я" => "YA", "Ч" => "CH", "С" => "S", "М" => "M", "И" => "I", "Т" => "T",
            "Ь" => "", "Б" => "B", "Ю" => "YU",
            "«" => "", "»" => "", " " => "-",  "/" => "-i-", '\(' => "", '\)' => "",
        );
        // Enforce the maximum component length
        $maxlength = 100;
        $text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($text))), $maxlength, '<br>', false)), 0, 1));
        //$text = substr(, 0, $maxlength);
        foreach ($matrix as $from => $to)
            $text = mb_eregi_replace($from, $to, $text);
// Optionally convert to lower case.
        if ($toLowCase) {
            $text = strtolower($text);
        }
        return $text;
    }
    /**
     * @param $type
     * @param null $text
     * @param int $opacity
     * @return string
     */
    public static function getColorBox($type, $text = null, $opacity = 1)
    {
        $result = '';
        $legends = is_array($type) ? $type : [[$type, $text, $opacity, '']];
        foreach ($legends as $legend) {
            $type = $legend[0];
            $text = isset($legend[1]) ? $legend[1] : null;
            $opacity = isset($legend[2]) ? $legend[2] : 1;
            $space = isset($legend[3]) ? $legend[3] : '&emsp;';
            switch ($type) {
                case 'muted':
                    $color = '166, 172, 144';
                    break;
                case 'primary':
                    $color = '46, 130, 255';
                    break;
                case 'success':
                    $color = '66, 170, 64';
                    break;
                case 'info':
                    $color = '126, 212, 208';
                    break;
                case 'warning':
                    $color = '248, 198, 92';
                    break;
                case 'danger':
                    $color = '238, 86, 82';
                    break;
                default:
                    $color = $type;
            }
            $color = 'background-color: rgba(' . $color . ', ' . $opacity . ');';
            if ($text) {
                $result .= '<label style="padding: 0 10px; ' . $color . '">&nbsp;</label>&nbsp; — ' . $text . $space;
            } else {
                $result .= $color;
            }
        }
        return $result;
    }
    /**
     * @param string $date
     * @return array|mixed
     */
    public static function dateForChart($date)
    {
        return empty($date) ? null : self::dateFor("d.m", $date);
    }
}