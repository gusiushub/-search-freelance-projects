<?php
# Скрипт граббера записей вк для группы (так же для страницы вк, чтоб на страницу вы где написано ваша группа ставите свой id без минуса)
# Слив для VKSERV.ru от Yury Rolix
# vk.com/id141418455
# Автор: Юрий Абрамов

use app\models\Task;

$start = microtime(true);
echo "<meta charset=\"utf-8\">";
$robber = new rob;

####### настройка скрипта ######

$grups = array ('-157585536'); #ID Группы откуда будут постить записили
$randomm = count($grups)-1;
//var_dump($randomm);exit;
//$randomm = mt_rand (0, count($grups)-1);
$grup = $grups[$randomm]; #Рандом групп

$robber->SetVar("token", "a5ce7136927fbb1a01516af9663855ca395fc82d6801d68824e33ee18841e0f74b09885f6789c4fa12673"); #токен от андройд
$robber->SetVar("id_group_rob", "$grup"); #не трогать
$robber->SetVar("id_group", "-173045687"); #ваша группа c минусом
$robber->SetVar("max_post", "100"); #Из скольки последних записей парсить ( тут нечего не трогать )

####### конец настройки #####


$robber->init();

class rob
{
    function init()
    {
        $query = $this->curl("https://api.vk.com/method/wall.get?owner_id=".$this->id_group_rob."&count=100&v=5.42&access_token=".$this->token."");
        $array_info = json_decode($query, true);

            for($i=0;$i<10;$i++) {

                echo 'Номер '.$i;
            $text = $array_info['response']['items'][$i]['text'];
            $id = $array_info['response']['items'][$i]['id'];
            $time_unix = $array_info['response']['items'][$i]['date'];
            $from_id = $array_info['response']['items'][$i]['from_id'];
            $link = 'https://vk.com/frwork_ru?w=wall' . $from_id . '_' . $id;
            $unic = Task::find()->where(['list_id' => $from_id . $id])->exists();
            if (!$unic) {
                echo '+';
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 5,
//                'subcategories_id' => $subcategories_id,
                    'categories_id' => 7,
                    'title' => 'vkGroupe_frwork_ru',
                    'text' => $text,
                    'price' => 'договор',
                    'list_id' => $from_id . $id,
                    'url' => $link,
                    'date' => date('Y-m-d'),
                    'time_unix' => $time_unix,//(int)(time()),
                ])->execute();
            }
        }
        exit;
//        var_dump($link);exit;
        //$count = rand(0,$this->max_post);
//        var_dump(count($array_info[response][items][$count][attachments]));exit;
        if(isset($array_info[response][items][$count][attachments]))
        {
            foreach ($array_info[response][items][$count][attachments] as $key => &$value)
            {
                $type = $array_info[response][items][$count][attachments][$key][type];
                $attachments .= $type.$array_info[response][items][$count][attachments][$key][$type][owner_id]."_".$array_info[response][items][$count][attachments][$key][$type][id].",";
            }
            $attachments = substr($attachments, 0, -1);
            $query = $this->curl("https://api.vk.com/method/wall.post?owner_id=".$this->id_group."&from_group=1&message=".urlencode($array_info[response][items][$count][text])."&attachments=".$attachments."&v=5.42&access_token=".$this->token."");
            $array_info = json_decode($query, true);
//            var_dump($query);exit;
            print_r($query);
        }
        else
        {
            $query = $this->curl("https://api.vk.com/method/wall.post?owner_id=".$this->id_group."&from_group=1&message=".urlencode($array_info[response][items][$count][text])."&v=5.42&access_token=".$this->token."");
            print_r($query);
        }
    }
    function SetVar($name_var, $value_var)
    {
        return $this->$name_var = $value_var;
    }
    function curl($url)
    {
        $ch = curl_init($url);
        curl_setopt ($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt ($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt ($ch,CURLOPT_SSL_VERIFYPEER,false);
        $response = curl_exec($ch);
        curl_close ($ch);
        return $response;
    }
}
echo "<br><br>Время выполнения: ".(microtime(true)-$start)." секунд.\nАвтор скрипта Юрий Абрамов.\nСлив для VKSERV.ru от Yury Rolix vk.com/id141418455";