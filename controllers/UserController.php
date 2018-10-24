<?php


namespace app\controllers;

use app\models\SettingForm;
use app\models\User;
use phpQuery;
use app\models\VkParser;
use yii\web\Controller;

class UserController extends Controller
{
//    private static $error_codes = [
//        "CURLE_UNSUPPORTED_PROTOCOL",
//        "CURLE_FAILED_INIT",
//
//        // Тут более 60 элементов, в архиве вы найдете весь список
//
//        "CURLE_FTP_BAD_FILE_LIST",
//        "CURLE_CHUNK_FAILED"
//    ];
//    public static function getPage($params = []){
//
//        if($params){
//
//            if(!empty($params["url"])){
//                $params["cookie"]["file"] = __DIR__."/cookie.txt";
//                $params["head"] = [
//                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
//                    'Accept-Encoding: gzip, deflate, br'
//                ];
////                $url = 'https://freelance.ru/projects/filter/?page=2';
//                $url = $params["url"];
//
//                $useragent      = !empty($params["useragent"]) ? $params["useragent"] : "Mozilla/5.0 (Windows NT 6.3; W…) Gecko/20100101 Firefox/57.0";
//                $timeout        = !empty($params["timeout"]) ? $params["timeout"] : 5;
//                $connecttimeout = !empty($params["connecttimeout"]) ? $params["connecttimeout"] : 5;
//                $head           = !empty($params["head"]) ? $params["head"] : false;
//
//                $cookie_file    = !empty($params["cookie"]["file"]) ? $params["cookie"]["file"] : false;
//                $cookie_session = !empty($params["cookie"]["session"]) ? $params["cookie"]["session"] : false;
//
//                $proxy_ip   = !empty($params["proxy"]["ip"]) ? $params["proxy"]["ip"] : false;
//                $proxy_port = !empty($params["proxy"]["port"]) ? $params["proxy"]["port"] : false;
//                $proxy_type = !empty($params["proxy"]["type"]) ? $params["proxy"]["type"] : false;
//
////                $headers = !empty($params["headers"]) ? $params["headers"] : false;
//                $headers = array(
//                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
//                    'Accept-Encoding: gzip, deflate, br',
//                    'Accept-Language: ru,en;q=0.9',
//                    'Cache-Control: max-age=0',
//                    'Connection: keep-alive',
//                );
//
//                $post = !empty($params["post"]) ? $params["post"] : false;
//                if($cookie_file){
//                    //file_put_contents(__DIR__."/".$cookie_file, "");
//                }
//                $ch = curl_init();
//
//                curl_setopt($ch, CURLOPT_URL, $url);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
//
//                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
//                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connecttimeout);
////                if($head){
////
////                    curl_setopt($ch, CURLOPT_HEADER, true);
////                    curl_setopt($ch, CURLOPT_NOBODY, true);
////                }
//                if(strpos($url, "https") !== false){
//
//                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
//                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
//                }
////                if($cookie_file){
////
////                    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__."/".$cookie_file);
////                    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__."/".$cookie_file);
////
////                    if($cookie_session){
////
////                        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
////                    }
////                }
////                if($proxy_ip && $proxy_port && $proxy_type){
////
////                    curl_setopt($ch, CURLOPT_PROXY, $proxy_ip.":".$proxy_port);
////                    curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy_type);
////                }
//
////                if($headers){
////
////                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
////                }
//
//
//// Далее продолжаем кодить тут
//
//                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//
//                $content = curl_exec($ch);
//                $info 	 = curl_getinfo($ch);
//
//                $error = false;
//
//                if($content === false){
//
//                    $data = false;
//
//                    $error["message"] = curl_error($ch);
//                    $error["code"] 	  = self::$error_codes[
//                    curl_errno($ch)
//                    ];
//                }else{
//
//                    $data["content"] = $content;
//                    $data["info"] 	 = $info;
//                }
//
//                curl_close($ch);
//
//                return [
//                    "data" 	=> $data,
//                    "error" => $error
//                ];
//            }
//        }
//
//        return false;
//    }


public function actionVk()
{
    $model = new VkParser();
    $model->Login('89859929791','gusigusi');
    $html = $model->search('https://vk.com/php2all');
    var_dump($html);
}


    /**
     * freelance.ru
     */
    public function actionFreelance()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://freelance.ru/projects/?spec=577");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = "Connection: keep-alive";
        $headers[] = "Upgrade-Insecure-Requests: 1";
        $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 YaBrowser/18.6.1.770 Yowser/2.5 Safari/537.36";
        $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
        $headers[] = "Accept-Encoding: gzip, deflate, br";
        $headers[] = "Accept-Language: ru,en;q=0.9";
        $headers[] = "Cookie: user_id=H8BvGVtj37BDw38zQKuaAg==; _ym_uid=1533271986837930734; _ym_d=1533271986; last_visit=1533266897502::1533277697502; _ym_isad=1; _ym_visorc_39101460=w";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        preg_match('/(?<=charset=)(.*)(?=(?:")|(?:\'))/iu',$result, $xd);
        if(strlen($xd['1'])!=0) {$charset=$xd['1'];} else {$charset='';}

        if($charset=='') {

            $zxdsl = iconv($this->detect_encoding($result),'utf-8', $result);
            $pq = phpQuery::newDocument($zxdsl);

            $posts = $pq->find('div.proj.not_public');

            foreach ($posts as $post) {
                $pqLink = pq($post);
                $price[] = $pqLink->find('b.visible-xs.cost_xs')->html();
            }

            foreach ($posts as $post) {
                $pqLink = pq($post);
                $title[] = $pqLink->find('a.ptitle > span')->html();
                $pq->find('b.visible-xs.cost_xs')->remove();
                $text[] = $pqLink->find('p > span')->html();
                $tag[] = $pqLink->find('li.proj-inf.status.pull-left')->html();
                $href[] = $pqLink->find('a.ptitle')->attr('href');
            }

            var_dump($href);
            var_dump($title);
            var_dump($text);
            var_dump($tag);
            var_dump($price);
            preg_match('/(windows-1251)/iu',$zxdsl, $xd).'<hr />';
            if(strlen($xd['1'])!=0) {$charset=$xd['1'];} else {$charset='';}

            if($charset!='') {$result=$zxdsl;}
            else
            {}

        }

    }

    /**
     * @param $string
     * @param int $pattern_size
     * @return mixed|string
     */
    private function detect_encoding($string, $pattern_size = 50)
    {
        $list = array('cp1251', 'utf-8', 'ascii', '855', 'KOI8R', 'ISO-IR-111', 'CP866', 'KOI8U');
        $c = strlen($string);
        if ($c > $pattern_size)
        {
            $string = substr($string, floor(($c - $pattern_size) /2), $pattern_size);
            $c = $pattern_size;
        }

        $reg1 = '/(\xE0|\xE5|\xE8|\xEE|\xF3|\xFB|\xFD|\xFE|\xFF)/i';
        $reg2 = '/(\xE1|\xE2|\xE3|\xE4|\xE6|\xE7|\xE9|\xEA|\xEB|\xEC|\xED|\xEF|\xF0|\xF1|\xF2|\xF4|\xF5|\xF6|\xF7|\xF8|\xF9|\xFA|\xFC)/i';

        $mk = 10000;
        $enc = 'ascii';
        foreach ($list as $item)
        {
            $sample1 = @iconv($item, 'cp1251', $string);
            $gl = @preg_match_all($reg1, $sample1, $arr);
            $sl = @preg_match_all($reg2, $sample1, $arr);
            if (!$gl || !$sl) continue;
            $k = abs(3 - ($sl / $gl));
            $k += $c - $gl - $sl;
            if ($k < $mk)
            {
                $enc = $item;
                $mk = $k;
            }
        }
        return $enc;
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
//            $user = new User();
            //$user->trialPeriod();
            return $this->render('index');
        }
    }

    public function actionSetting()
    {
        if (!\Yii::$app->user->isGuest) {
            $model = new SettingForm();
            return $this->render('setting', [
                'model' => $model,
            ]);
        }
    }

    public function actionPay()
    {
        return $this->render('pay');
    }

}