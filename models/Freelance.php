<?php

namespace app\models;

use phpQuery;
use Yii;
use yii\base\Model;

class Freelance extends Model {

    /**
     *
     */
    public function freelance()
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

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
                $id[] = $pqLink->find('span.anchor.proj_anchor')->attr('id');
                $id[] = str_replace ('proj','',$id);
                var_dump($href );
                var_dump($title);
                var_dump($text );
                var_dump($tag  );
                var_dump($price);
                var_dump($id   );
                $unic =Task::find()->where(['list_id' => $id])->exists();
                if(!$unic){
//                    var_dump($unic);
                    Yii::$app->db->createCommand()->insert('task', [
                        'site_id' => 4,
                        'subcategories_id' => 2,
                        'title' => $title[0],
                        'text' => $text[0],
                        'price' => $price[0],
                        'list_id' => (int)$id[1][0],
                        'url' => 'https://freelance.ru'.$href[0],
                        'date' => date('Y-m-d'),
                        'time_unix' => (int)(time()),
                    ])->execute();
                }
            }

//            var_dump($href[0]);
//            var_dump($title[0]);
//            var_dump($text[0]);
//            var_dump($tag[0]);
//            var_dump($price[0]);
//            var_dump($id[0]);

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
}