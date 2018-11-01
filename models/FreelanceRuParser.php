<?php
namespace app\models;
use yii\base\Model,
    app\components\helpers\FunctionHelper;


class FreelanceRuParser extends Model
{
    /**
     * @param $pages
     * @return bool
     * @throws \Exception
     */
    public function getUrlProgects($pages)
    {
        $pagesArr = [];
        $pagesArr[] = 'https://freelance.ru/projects/filter/?page=1';
        if ($pages > 1) {
            for ($i = 1; $i <= $pages; $i++) {
                $pagesArr[] = 'https://freelance.ru/projects/filter/?page=' . $i;
            }
        }
        foreach ($pagesArr as $page) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
            curl_setopt($curl, CURLOPT_TIMEOUT, 10); // times out after 4s
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($curl, CURLOPT_URL, $page);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
            $data = iconv("windows-1251", "utf-8", curl_exec($curl));
            //$data = curl_exec($curl);
            $dataList = FunctionHelper::resOtDo(
                '<div class="projects" >',
                '<div><div class="pager_nav" >',
                $data
            );
            $pcs = explode('</ul></div><div class="proj', $dataList[0]);
            $result = [];
            $i = '';
            foreach ($pcs as $item) {
                //return $item;
                $href = FunctionHelper::resOtDo('<a class="ptitle" href="', '" >', $item);
                if (!$href[0]) {
//                    continue;
                }
                $id = FunctionHelper::resOtDo('data-project-id="', '" >', $item);
                if (!$id[0]) {
//                    continue;
                }
                $budget = FunctionHelper::resOtDo('btn-block hidden-xs" >', ' р.</a>', $item);
                if (!$budget[0]) {
//                    continue;
                }
                $createdStr = FunctionHelper::resOtDo('<li title="', '"', $item);
                if (!$createdStr[0]) {
//                    continue;
                }
                $name = FunctionHelper::resOtDo('.html" ><span>', '</span></a>', $item);
                if (!$name[0]) {
//                    continue;
                }
                $create = explode(' ', str_replace('Поднято ', '', $createdStr[0]));
                $result[] = [
                    'url' => 'https://freelance.ru' . $href[0],
                    'id' => $id[0],
                    'published' => FunctionHelper::dateForMysql($create[1]) . ' ' . $create[2],
                    'budget' => str_replace(' ', '', $budget[0]),
                    'name' => $name[0],
                ];
            }
            foreach ($result as $item) {

                if (!empty($item['url']) && !empty($item['id'])) {
                    if ($model = $this->findUrl($item['url'], 4)) {
//                        var_dump($item); exit;

//                        $model->url = $item['url'];
                        $model->site_id = 4;
                        $model->list_id = $item['id'];
//                        $model->added = date('Y-m-d H:i:s');
//                        $model->published = $item['published'];
                        if (!empty($item['budget']) and $item['budget']!=''){
                            $model->price = $item['budget'];
                        }else{
                            $model->price = 0;
                        }

                        $model->currency = 'RUB';
                        $model->title = $item['name'];
                        $model->save(false);
                        $this->getProgects($item['url'],$item['id']);
                    }
                }
            }
        }
        return true;
    }


    /**
     * @param $pageUrl - ссылка на отдельный пост
     * @param $list_id - id этого поста
     * @throws \Exception
     */
    public function getProgects($pageUrl, $list_id)
    {

        $unic = Task::find()->where('list_id=:list_id',[':list_id' => $list_id])->exists();
//        if (!$unic) {
//        foreach ($model as $item) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
            curl_setopt($curl, CURLOPT_TIMEOUT, 10); // times out after 4s
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($curl, CURLOPT_URL, $pageUrl);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
            $data = iconv("windows-1251", "utf-8", curl_exec($curl));
//            $data = curl_exec($curl);
            //--START--Текст проекта
            $textDiv = '<table id="proj_table" >';
            if (stristr($data, $textDiv) === FALSE) {
//                $item->setError();
//                continue;
            }
            $textProjectDiv = FunctionHelper::resOtDo($textDiv, '</table>', $data);
            if (!$textProjectDiv[0]) {
//                $item->setError();
//                continue;
            }
            $textProjectDiv = strstr($textProjectDiv[0], '<tr class="pub_keys_row" >', true);
            $text = $textDiv . $textProjectDiv . '</table>';
            //--END--Текст проекта
            //--START--Категория проекта
            $original = $this->getOriginalCategories();
            $blockCategories = '<ul class="breadcrumb" >';
            if (stristr($data, $blockCategories) === FALSE) {
//                $item->setError();
//                continue;
            }
            $textCategories2 = FunctionHelper::resOtDo($blockCategories, '</ul>', $data);
            if (!$textCategories2[0]) {
//                $item->setError();
//                continue;
            }
            $textCategories3 = FunctionHelper::resOtDo('class="active" >', '</a>', $textCategories2[0]);
            if (!$textCategories3[0]) {
//                $item->setError();
//                continue;
            }
            // return $textCategories3[0];
            $category = $textCategories3[0];
            foreach ($original as $key => $value) {
                $body = FunctionHelper::lightCyrillicToLatin($category);
                $search = FunctionHelper::lightCyrillicToLatin($original[$key]['freelance']);
                if (preg_match("/$search/", $body)) {
                    $category = $original[$key]['our'];
                    break;
                }
            }
        $item = Task::find()->where('list_id=:list_id',[':list_id' => $list_id])->one();
//            $task = new Task();
//            var_dump($textCategories3);exit;
//            var_dump(htmlspecialchars_decode($text));
//            $item->category = $category > 0 ? $category : 9;
//        $item->title = $title;
//        $item->price = $budget;
//        $item->currency = 'RUB';
        $item->time_unix = time();
//        $item->url = $pageUrl;
        $item->text = htmlspecialchars_decode($text);
//            $item->parse = Parser::PARSE_SUCCESS;
//        $item->site_id = 4;
        return $item->save(false);
//        }
    }
    /**
     *
     */
    public function getClearBags()
    {
        $model = Task::find()->where(['parse' => 0, 'source' => 'freelance.ua'])->all();
        foreach ($model as $item) {
            $item->setError();
        }
    }
    /**
     * @return array
     */
    public function getOriginalCategories()
    {
        /**
         *
         */
        return [
            ['freelance' => 'Фотография', 'our' => 5],
            ['freelance' => 'Флеш', 'our' => 2],
            ['freelance' => 'Фирменный стиль', 'our' => 2],
            ['freelance' => 'Тексты', 'our' => 6],
            ['freelance' => 'Реклама / презентации', 'our' => 9],
            ['freelance' => 'Разработка игр / графика', 'our' => 3],
            ['freelance' => 'Промышленный дизайн', 'our' => 2],
            ['freelance' => 'Полиграфический дизайн', 'our' => 2],
            ['freelance' => 'Переводы', 'our' => 6],
            ['freelance' => 'Обучение / консультации', 'our' => 8],
            ['freelance' => 'Музыка / звук', 'our' => 9],
            ['freelance' => 'Менеджмент', 'our' => 9],
            ['freelance' => 'Медиадизайн / анимация', 'our' => 2],
            ['freelance' => 'Маркетинг и реклама', 'our' => 9],
            ['freelance' => 'Инженерия', 'our' => 9],
            ['freelance' => 'Графический дизайн', 'our' => 2],
            ['freelance' => 'Видео', 'our' => 5],
            ['freelance' => 'Веб-дизайн', 'our' => 2],
            ['freelance' => 'Бытовые услуги', 'our' => 8],
            ['freelance' => 'Аутсорсинг / консалтинг', 'our' => 8],
            ['freelance' => 'Архитектура / интерьер', 'our' => 9],
            ['freelance' => 'Арт / иллюстрации', 'our' => 2],
            ['freelance' => 'IT и Программирование', 'our' => 3],
            ['freelance' => '3D графика', 'our' => 2]
        ];
    }
    /**
     * @param $url
     * @param $source
     * @return Parser|null|static
     */
    protected function findUrl($list_id, $site_id)
    {
        if (($model = Task::findOne(['site_id' => $list_id, 'list_id' => $site_id])) !== null) {
            return null;
        } else {
            return new Task;
        }
    }
    /**
     * @return mixed
     */
    public static function getDates()
    {
        $result = Task::find()->select('DATE(added) as date')->distinct()->asArray()->orderBy(['added' => SORT_ASC])->all();
        return $result;
    }
    public static function getReportByParsing()
    {
        $getDays = self::getDates();
        $projects = [];
        foreach ($getDays as $day) {
            $days[] = date(FunctionHelper::dateForChart($day['date']));
            $where = [
                'AND',
                ['parse' => Task::PARSE_SUCCESS],
                ['between', "added", $day['date'] . ' 00:00:00', $day['date'] . ' 23:59:59']
            ];
            $projects[] = floatval(Task::find()->where($where)->count());
        }
        return [$days, $projects];
    }
}