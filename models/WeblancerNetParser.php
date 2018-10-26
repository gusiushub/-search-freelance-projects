<?php
namespace app\models;
use app\models\Projects;
use yii\base\Model,
    app\components\helpers\FunctionHelper,
//    app\components\helpers\FunctionHelper,
    app\models\Parser;


class WeblancerNetParser extends Model
{
    //public $url=0;
    public function getUrlProgects($pages)
    {
        $pagesArr = [];
        $pagesArr[] = 'https://www.weblancer.net/jobs/';
        if ($pages > 1) {
            for ($i = 1; $i <= $pages; $i++) {
                $pagesArr[] = 'https://www.weblancer.net/jobs/?page=' . $i;
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
            $dataList = FunctionHelper::resOtDo(
                '<div class="cols_table">',
                '<div class="pagination_box">',
                $data
            );
            $pcs = explode('<div class="row">', $dataList[0]);
            $result = [];
            foreach ($pcs as $item) {
                $href = FunctionHelper::resOtDo('href="', '">', $item);
                if (!$href[0]) {
                    continue;
                }
                $partUrl = explode('/', $href[0]);
                $strWithId = explode('-', $partUrl[3]);
                $partId = count($strWithId) - 1;
                $result[] = ['url' => '/' . $partUrl[1] . '/' . $partUrl[2] . '/' . $partUrl[3], 'id' => $strWithId[$partId]];
            }

            foreach ($result as $item) {
                $unic =Task::find()->where(['list_id' => $item['id']])->andWhere('url=:url',[':url'=>$item['url']])->exists();
                if(!$unic) {
//                var_dump($content);
                if (!empty($item['url']) && !empty($item['id']) ) {

                    if ($model = $this->findUrl($item['id'], 2)) {

//                        $model->title= $content['title'];
//                        $model->text= $content['text'];
                        $model->site_id = 2;
                        $model->list_id = $item['id'];
                        $model->date = date('Y-m-d');
                        $model->save(false);

//                        $content = $this->getProgects($item['url']);

                    }
                    $this->getProgects($item['url'],$item['id']);
                }
                }



//            }
            }

        }

        //return true;
    }
    public function getProgects($url2,$list_id)
    {
        $item = Task::find()->where('list_id=:list_id',[':list_id' => $list_id])->one();
//        foreach ($model as $item) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
            curl_setopt($curl, CURLOPT_TIMEOUT, 10); // times out after 4s
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($curl, CURLOPT_URL, 'https://www.weblancer.net' . $url2);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
            $data = iconv("windows-1251", "utf-8", curl_exec($curl));
            //--START--Название проекта
            $nameH1 = '<h1>';
            if (stristr($data, $nameH1) === FALSE) {
                $item->setError();
//                continue;
            }
            $name = FunctionHelper::resOtDo($nameH1, '</h1>', $data);
            if (!$name[0] || $name[0] == 'Вы не авторизованы' || $name[0] == 'Ошибка 404: Страница не найдена') {
                $item->setError();
//                continue;
            }
            //--END--Название проекта
            //--START--Дату публикации
            $publishDiv = '<div class="float-right text-muted hidden-xs-down">';
            if (stristr($data, $publishDiv) === FALSE) {
                $item->setError();
//                continue;
            }
            $publish = FunctionHelper::resOtDo($publishDiv, '</div>', $data);
            if (!$publish[0]) {
                $item->setError();
//                continue;
            }
            $publishDate = FunctionHelper::resOtDo('data-timestamp="', '" class="time_ago"', $publish[0]);
            if (!$publishDate[0]) {
                $item->setError();
//                continue;
            }
            //--END--Дату публикации
            //--START--Текст проекта
            $textDiv = '<div class="col-12 text_field">';
            if (stristr($data, $textDiv) === FALSE) {
                $item->setError();
//                continue;
            }
            $textProjectDiv = FunctionHelper::resOtDo($textDiv, '</div>', $data);
            if (!$textProjectDiv[0]) {
                $item->setError();
//                continue;
            }
            $textProjectP = FunctionHelper::resOtDo('<p>', '</p>', $data);
            if (!$textProjectP[0]) {
                $item->setError();
//                continue;
            }
            $projectText = $textProjectP[0];

            //--END--Текст проекта
            //--START--Бюджет проекта
            $budgetDiv = 'title amount';
            if (stristr($data, $budgetDiv) === FALSE) {
                $item->price = 1;
                $item->currency = 'UAH';
            } else {
                $budget = FunctionHelper::resOtDo('<span class="title amount">', '</span>', $data);
                if ($budget[0]) {
                    $item->price = str_replace('$', '', $budget[0]);
                    $item->currency = 'USD';
                } else {
                    $item->price = 1;
                    $item->currency = 'UAH';
                }
            }
            //--END--Бюджет проекта
            //--START--Категория проекта
            $original = $this->getOriginalCategories();
            $blockCategories = '<span itemprop="itemListElement"';
            if (stristr($data, $blockCategories) === FALSE) {
                $item->setError();
//                continue;
            }
            $textCategories = FunctionHelper::resOtDo($blockCategories, '</a>', $data);
            if (!$textCategories[0]) {
                $item->setError();
//                continue;
            }
            $category = FunctionHelper::resOtDo('<span itemprop="name">', '</span>', $textCategories[0]);
            if (!$category[0]) {
                $item->setError();
//                continue;
            }

            foreach ($original as $key => $value) {
                $body = FunctionHelper::lightCyrillicToLatin($category[0]);
                $search = FunctionHelper::lightCyrillicToLatin($original[$key]['weblancer']);

                if (preg_match("/$search/", $body)) {

                    $category = $original[$key]['our'];

                    break;
                }
            }

            $unic =Task::find()->where(['list_id' => $list_id])->exists();
            if($unic) {
                $item->categories_id = $category > 0 ? $category : 9;
                $item->text = htmlspecialchars_decode($projectText);
                $item->title = $name[0];
                $item->url = $url2;
                $item->date = date('Y-m-d');
                $item->save(false);
            }

//        }
    }
    /**
     *
     */
    public function getClearBags()
    {
        $model = Parser::find()->where(['parse' => 0, 'source' => 'weblancer.net'])->all();
        foreach ($model as $item) {
            $item->setError();
        }
    }
    /**
     * @return array
     */
    public function getOriginalCategories()
    {
        return [
            ['weblancer' => 'Наполнение сайтов', 'our' => 7],
            ['weblancer' => 'Системное администрирование', 'our' => 7],
            ['weblancer' => 'Служба поддержки', 'our' => 7],
            ['weblancer' => 'Архитектура зданий', 'our' => 9],
            ['weblancer' => 'Интерьеры и Экстерьеры', 'our' => 2],
            ['weblancer' => 'Ландшафтный дизайн', 'our' => 2],
            ['weblancer' => 'Машиностроение', 'our' => 9],
            ['weblancer' => 'Чертежи и Схемы', 'our' => 9],
            ['weblancer' => 'Анимация', 'our' => 5],
            ['weblancer' => 'Аудиомонтаж', 'our' => 5],
            ['weblancer' => 'Видеомонтаж', 'our' => 5],
            ['weblancer' => 'Музыка и Звуки', 'our' => 5],
            ['weblancer' => 'Озвучивание', 'our' => 5],
            ['weblancer' => 'Презентации', 'our' => 5],
            ['weblancer' => 'Баннеры', 'our' => 2],
            ['weblancer' => 'Дизайн мобильных приложений', 'our' => 2],
            ['weblancer' => 'Дизайн сайтов', 'our' => 2],
            ['weblancer' => 'Иконки и Пиксель-арт', 'our' => 2],
            ['weblancer' => 'Интерфейсы игр и программ', 'our' => 2],
            ['weblancer' => 'HTML-верстка', 'our' => 3],
            ['weblancer' => 'Веб-программирование', 'our' => 3],
            ['weblancer' => 'Интернет-магазины', 'our' => 3],
            ['weblancer' => 'Сайты «под ключ»', 'our' => 3],
            ['weblancer' => 'Системы управления (CMS)', 'our' => 3],
            ['weblancer' => 'Тестирование сайтов', 'our' => 3],
            ['weblancer' => '3D-графика', 'our' => 5],
            ['weblancer' => 'Иллюстрации и Рисунки', 'our' => 5],
            ['weblancer' => 'Обработка фотографий', 'our' => 5],
            ['weblancer' => 'Фотосъемка', 'our' => 5],
            ['weblancer' => 'Верстка полиграфии', 'our' => 3],
            ['weblancer' => 'Дизайн продукции', 'our' => 2],
            ['weblancer' => 'Логотипы и Знаки', 'our' => 2],
            ['weblancer' => 'Наружная реклама', 'our' => 2],
            ['weblancer' => 'Фирменный стиль', 'our' => 2],
            ['weblancer' => '1С-программирование', 'our' => 3],
            ['weblancer' => 'Базы данных', 'our' => 3],
            ['weblancer' => 'Встраиваемые системы', 'our' => 3],
            ['weblancer' => 'ПО для мобильных устройств', 'our' => 3],
            ['weblancer' => 'Прикладное ПО', 'our' => 3],
            ['weblancer' => 'Разработка игр', 'our' => 3],
            ['weblancer' => 'Системное программирование', 'our' => 3],
            ['weblancer' => 'Тестирование ПО', 'our' => 3],
            ['weblancer' => 'Контекстная реклама', 'our' => 4],
            ['weblancer' => 'Поисковые системы (SEO)', 'our' => 4],
            ['weblancer' => 'Социальные сети (SMM и SMO)', 'our' => 4],
            ['weblancer' => 'Копирайтинг', 'our' => 6],
            ['weblancer' => 'Нейминг и Слоганы', 'our' => 6],
            ['weblancer' => 'Переводы', 'our' => 6],
            ['weblancer' => 'Продающие тексты', 'our' => 6],
            ['weblancer' => 'Редактирование и Корректура', 'our' => 6],
            ['weblancer' => 'Рерайтинг', 'our' => 6],
            ['weblancer' => 'Стихи, Песни и Проза', 'our' => 6],
            ['weblancer' => 'Сценарии', 'our' => 6],
            ['weblancer' => 'Транскрибация', 'our' => 6],
            ['weblancer' => 'Подбор персонала (HR)', 'our' => 8],
            ['weblancer' => 'Управление продажами', 'our' => 8],
            ['weblancer' => 'Управление проектами', 'our' => 8],
            ['weblancer' => 'Контрольные, Задачи и Тесты', 'our' => 8],
            ['weblancer' => 'Рефераты, Курсовые и Дипломы', 'our' => 8],
            ['weblancer' => 'Уроки и Репетиторство', 'our' => 8],
            ['weblancer' => 'Бухгалтерские услуги', 'our' => 8],
            ['weblancer' => 'Финансовые услуги', 'our' => 8],
            ['weblancer' => 'Юридические услуги', 'our' => 8],
        ];
    }
    /**
     * @param $url
     * @param $source
     * @return Parser|null|static
     */
    protected function findUrl($list_id, $site_id)
    {
        if (($model = Task::findOne(['site_id' => $site_id, 'list_id' => $list_id])) !== null) {
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
        $result = Parser::find()->select('DATE(added) as date')->distinct()->asArray()->orderBy(['added' => SORT_ASC])->all();
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
                ['parse' => Parser::PARSE_SUCCESS],
                ['between', "added", $day['date'] . ' 00:00:00', $day['date'] . ' 23:59:59']
            ];
            $projects[] = floatval(Parser::find()->where($where)->count());
        }
        return [$days, $projects];
    }
}