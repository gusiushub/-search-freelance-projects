<?php


namespace app\controllers;

use app\models\FreelancehuntComParser;
use app\models\Payments;
use app\models\SettingForm;
use app\models\Task;

use app\models\User;
use phpQuery;
//use app\models\VkParser;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{

    public $enableCsrfValidation = false;

//    public function actionVk()
//    {
//        $model = new VkParser();
//        $model->Login('89859929791','gusigusi');
//        $html = $model->search('https://vk.com/php2all');
//        var_dump($html);
//    }

    public function actionTest()
    {
        $model = new FreelancehuntComParser();
        $posts = $model->getProjectsByApi();
        foreach ($posts as $post) {
            $unic =Task::find()->where(['list_id' => $post['project_id']])->exists();
            if(!$unic){
                echo "Запись в бд нового поста \n";
                Yii::$app->db->createCommand()->insert('task', [
                    'site_id' => 6,
//                    'categories_id' => $category,
//                    'subcategories_id' => $subCutegory,
//                    'title' => $job['title'],
                    'text' => $post['description'],
//                    'price' => trim($job['budget']),
                    'list_id' => $post['project_id'],
                    'url' => 'https://fl.ru'.$post['url'],
                    'date' => date('Y-m-d'),
                    'time_unix' => (int)(time()),
                ])->execute();
            }
            }
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

    public function actionPayError()
    {
        return $this->render('pay');
    }

    public function actionIndex()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->controller->enableCsrfValidation = false;
        if (!\Yii::$app->user->isGuest) {

            return $this->render('index');
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
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
        var_dump(time()+2678400);
        if (isset($_POST['ik_inv_st'])){

            if ($_POST['ik_inv_st']=='success'){
                //try {
                    Yii::$app->db->createCommand()->insert('payments', [
                        'user_id' => Yii::$app->user->identity->id,
                        'status' => $_POST['ik_inv_st'],
                        'cod' => $_POST['ik_pm_no'],
                        'ik_inv_id' => $_POST['ik_inv_id'],
                        'date' => date('Y-m-d'),
                        'ik_co_id' => $_POST['ik_co_id'],
                    ])->execute();

//                    $sql = 'UPDATE user SET paid_to = ' . time() + 2678400 .'" WHERE id=' . Yii::$app->user->id;
                    $paid_to = time() + 2678400;
                    return Yii::$app->db->createCommand()->update('user',['paid_to'=> $paid_to],'id>'.Yii::$app->user->id)->execute();
//                }catch (\Exception $e){
//                    throw $e;
//                }
//                $user = User::find()->where('id=:id',[':id'=>Yii::$app->user->identity->id])->one();
//                $user->paid_to = time()+2678400;
//                $user->save(false);
            }
        }

        return $this->render('pay');
    }


}