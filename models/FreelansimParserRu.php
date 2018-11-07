<?php
namespace app\models;


use yii\base\Model,
    app\components\helpers\FunctionHelper;


class FreelansimParserRu extends Model
{
//    public function getCategory()
//    {
//
//    }

    /**
     * @param $pages
     * @param $category
     */
    public function getUrlProgects($pages, $category)
    {
        $pagesArr = [];
        $pagesArr[] = 'https://freelansim.ru/?categories='.$category;
        if ($pages > 1) {
            for ($i = 1; $i <= $pages; $i++) {
                $pagesArr[] = 'https://freelansim.ru/tasks?categories='.$category.'&page=' . $i;
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
            //$data = iconv("windows-1251", "utf-8", curl_exec($curl));
            $data = curl_exec($curl);
            $dataList = FunctionHelper::resOtDo(
                '<ul class=\'content-list content-list_tasks\' id=\'tasks_list\'>',
                '<div id=\'pagination\'>',
                $data
            );
            $pcs = explode('li class=\'content-list__item\'', $dataList[0]);
            $result = [];
            $i = '';
            foreach ($pcs as $item) {
                $nameH1 = '<div class=\'task__title\' title=\'';
                if (stristr($item, $nameH1) === FALSE) {
                    continue;
                }
                $name = FunctionHelper::resOtDo($nameH1, '\'>', $item);
                $divUrl = FunctionHelper::resOtDo($nameH1, '</div>', $item);
                $href = FunctionHelper::resOtDo('<a href="', '">', $divUrl[0]);

                if (!$href[0]) {
                    continue;
                }
                $id = explode('/', $href[0]);
                $result[] = ['url' => 'https://freelansim.ru' . $href[0], 'name' => $name[0], 'id' => $id[2]];

            }
            foreach ($result as $item) {
                $unic =Task::find()->where(['list_id' => $item['id']])->andWhere('url=:url',[':url'=>$item['url']])->exists();
                if(!$unic) {
                if (!empty($item['url']) && !empty($item['id'])) {
                    if ($model = $this->findUrl($item['url'], 3)) {

                        $model->site_id = 3;
                        $model->list_id = $item['id'];
                        $model->date = date('Y-m-d');
                        $model->save(false);
                    }
                    $this->getProgects($item['url'],$item['id']);
                }
            }
            }
        }
        //return true;
    }

    /**
     * @param $url2
     * @param $list_id
     */
    public function getProgects($url2, $list_id)
    {
        $item = Task::find()->where('list_id=:list_id',[':list_id' => $list_id])->one();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // times out after 4s
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($curl, CURLOPT_URL, $url2);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
//            $data = iconv("windows-1251", "utf-8", curl_exec($curl));
        $data = curl_exec($curl);

        //--START--Название проекта
        $nameH1 = '<h2 class=\'task__title\'>';
        if (stristr($data, $nameH1) === FALSE) {
//            $item->setError();
        }
        $name = FunctionHelper::resOtDo($nameH1, '</h2>', $data);

        //--END--Название проекта
        //--START--Дату публикации
        $publishDiv = '<div class=\'task__meta\'>';
        if (stristr($data, $publishDiv) === FALSE) {
//            $item->setError();
        }
        $publish = FunctionHelper::resOtDo($publishDiv, '</div>', $data);
        if (!$publish[0]) {
//            $item->setError();
        }

        //--END--Дату публикации
        //--START--Текст проекта
        $textProjectDiv = FunctionHelper::resOtDo('<div class=\'task__description\'>', '</div>', $data);
        if (!$textProjectDiv[0]) {
//            $item->setError();
        }
        $projectText = $textProjectDiv[0];
        //--END--Текст проекта
        //--START--Бюджет проекта
        $priceSpan = '<div class=\'task__finance\'>';
        if (stristr($data, $priceSpan) === FALSE) {
            $budget = 1;
            $currency = 'RUR';
        } else {
            $budgetSpan = FunctionHelper::resOtDo($priceSpan, '</span>', $data);
            if (stristr($budgetSpan[0], '<span class=\'negotiated_price\'') === FALSE) {
                $budgetRur = explode(' руб.', $budgetSpan[0]);
                $budget = str_replace(' ', '', strip_tags($budgetRur[0]));
            } else {
                $budget = 1;
                $currency = 'RUR';
            }
            $currency = 'RUR';
        }
        //--END--Бюджет проекта

        $publish = explode(' ',$publish[0]);
        $day = $publish[0];
        $month = $publish[1];
        $year = $publish[2];
        $year = str_replace(',','',$year);
        $time = trim($publish[3]);
//            var_dump($time);
        $item->title = $name[0];
        $item->text = htmlspecialchars_decode($projectText);
        $item->price = $budget;
        $item->currency = $currency;
        $item->date = date('Y-m-d');
        $item->url = $url2;
        $item->time_unix = (int)(time());
        $item->save(false);
    }


    /**
     *
     */
    public function getClearBags()
    {
        $model = Task::find()->where(['list_id' => 0, 'source' => 'freelance.ua'])->all();
        foreach ($model as $item) {
//            $item->setError();
        }
    }


    /**
     * @return array
     */
    public function getOriginalCategories()
    {
        return [
            [3 => '3DГрафика', 'our' => 2],
            [3 => '3DАнимация', 'our' => 2],
            [3 => '3DИллюстрации', 'our' => 2],
            [3 => '3DМоделирование', 'our' => 2],
            [3 => '3DПерсонажи', 'our' => 2],
            [3 => 'Визуализация/3D', 'our' => 2],
            [3 => 'Анимация/Мультипликация', 'our' => 2],
            [3 => 'Анимация', 'our' => 2],
            [3 => 'Персонажи', 'our' => 2],
            [3 => 'Сценариидляанимации', 'our' => 2],
            [3 => 'Арт', 'our' => 2],
            [3 => 'Аэрография', 'our' => 2],
            [3 => 'Граффити', 'our' => 2],
            [3 => 'Живопись', 'our' => 2],
            [3 => 'Комиксы', 'our' => 2],
            [3 => 'Концепт-арт', 'our' => 2],
            [3 => 'Пиксел-арт', 'our' => 2],
            [3 => 'Рисункиииллюстрации', 'our' => 2],
            [3 => 'Хенд-мейд', 'our' => 2],
            [3 => 'Архитектура/Интерьер', 'our' => 9],
            [3 => 'Архитектура', 'our' => 9],
            [3 => 'Интерьеры', 'our' => 9],
            [3 => 'Ландшафтныйдизайн/Генплан', 'our' => 9],
            [3 => 'Макетирование', 'our' => 9],
            [3 => 'Аудио/Видео', 'our' => 5],
            [3 => 'Аудиомонтаж', 'our' => 5],
            [3 => 'Видеодизайн', 'our' => 5],
            [3 => 'Видеоинфографика', 'our' => 5],
            [3 => 'Видеомонтаж', 'our' => 5],
            [3 => 'Видеопрезентации', 'our' => 5],
            [3 => 'Видеосъемка', 'our' => 5],
            [3 => 'Диктор', 'our' => 5],
            [3 => 'Музыка/Звуки', 'our' => 5],
            [3 => 'Раскадровки', 'our' => 5],
            [3 => 'Режиссура', 'our' => 5],
            [3 => 'Свадебноевидео', 'our' => 5],
            [3 => 'Созданиесубтитров', 'our' => 5],
            [3 => 'Аутсорсингиконсалтинг', 'our' => 5],
            [3 => 'Бизнес-консультирование', 'our' => 8],
            [3 => 'Бухгалтерия', 'our' => 8],
            [3 => 'Виртуальныйассистент', 'our' => 8],
            [3 => 'Кадровыйучетизарплата', 'our' => 8],
            [3 => 'Обработказаказов', 'our' => 8],
            [3 => 'Обслуживаниеклиентовиподдержка', 'our' => 8],
            [3 => 'Поддержкапотелефону', 'our' => 8],
            [3 => 'Системыуправленияпредприятием', 'our' => 1],
            [3 => 'Статистическийанализ', 'our' => 8],
            [3 => 'Техническаяподдержка', 'our' => 7],
            [3 => 'Финансовыйконсультант', 'our' => 8],
            [3 => 'Юриспруденция', 'our' => 8],
            [3 => 'Дизайн', 'our' => 2],
            [3 => 'Баннеры', 'our' => 2],
            [3 => 'Дизайнвыставочныхстендов', 'our' => 2],
            [3 => 'Дизайнинтерфейсовприложений', 'our' => 2],
            [3 => 'Дизайнсайтов', 'our' => 2],
            [3 => 'Дизайнупаковки', 'our' => 2],
            [3 => 'Дизайнермашиннойвышивки', 'our' => 2],
            [3 => 'Дизайнинтерфейса', 'our' => 2],
            [3 => 'Инфографика', 'our' => 2],
            [3 => 'Картография', 'our' => 2],
            [3 => 'Логотипы', 'our' => 2],
            [3 => 'Наружнаяреклама', 'our' => 2],
            [3 => 'Полиграфическийдизайн', 'our' => 2],
            [3 => 'Презентации', 'our' => 2],
            [3 => 'Промышленныйдизайн', 'our' => 2],
            [3 => 'Разработкашрифтов', 'our' => 2],
            [3 => 'Техническийдизайн', 'our' => 2],
            [3 => 'Фирменныйстиль', 'our' => 2],
            [3 => 'Инжиниринг', 'our' => 9],
            [3 => 'Водоснабжение/Канализация', 'our' => 9],
            [3 => 'Газоснабжение', 'our' => 9],
            [3 => 'Конструкции', 'our' => 9],
            [3 => 'Машиностроение', 'our' => 9],
            [3 => 'Отопление/Вентиляция', 'our' => 9],
            [3 => 'Разработкарадиоэлектронныхсистем', 'our' => 9],
            [3 => 'Слаботочныесети/Автоматизация', 'our' => 9],
            [3 => 'Сметы', 'our' => 9],
            [3 => 'Технология', 'our' => 9],
            [3 => 'Чертежи/Схемы', 'our' => 9],
            [3 => 'Электрика', 'our' => 9],
            [3 => 'Менеджмент', 'our' => 8],
            [3 => 'Арт-директор', 'our' => 8],
            [3 => 'Менеджерпоперсоналу', 'our' => 8],
            [3 => 'Менеджерпопродажам', 'our' => 8],
            [3 => 'Менеджерпроектов', 'our' => 8],
            [3 => 'Контент-менеджер', 'our' => 6],
            [3 => 'Управлениерепутациейонлайн', 'our' => 4],
            [3 => 'Мобильныеприложения', 'our' => 3],
            [3 => 'GoogleAndroid', 'our' => 3],
            [3 => 'iOS', 'our' => 3],
            [3 => 'WindowsPhone', 'our' => 3],
            [3 => 'Дизайнмобильныхприложений', 'our' => 2],
            [3 => 'Обучениеиконсультации', 'our' => 8],
            [3 => 'Гуманитарныедисциплины', 'our' => 9],
            [3 => 'Дошкольноеобразование', 'our' => 9],
            [3 => 'Иностранныеязыки', 'our' => 6],
            [3 => 'Психолог', 'our' => 8],
            [3 => 'Путешествия', 'our' => 9],
            [3 => 'Репетиторы/Преподаватели', 'our' => 8],
            [3 => 'Рефераты/Курсовые/Дипломы', 'our' => 9],
            [3 => 'Техническиедисциплины', 'our' => 9],
            [3 => 'Продвижение(SEO,SMM)', 'our' => 4],
            [3 => 'Контекстнаяреклама', 'our' => 4],
            [3 => 'SEOтексты', 'our' => 4],
            [3 => 'Поисковыесистемы', 'our' => 4],
            [3 => 'Социальныесети', 'our' => 4],
            [3 => 'Переводы', 'our' => 6],
            [3 => 'Корреспонденция/Деловаяпереписка', 'our' => 6],
            [3 => 'ЛокализацияПО,сайтовиигр', 'our' => 6],
            [3 => 'Переводтекстовобщейтематики', 'our' => 6],
            [3 => 'Редактированиепереводов', 'our' => 6],
            [3 => 'Техническийперевод', 'our' => 6],
            [3 => 'Устныйперевод', 'our' => 6],
            [3 => 'Художественныйперевод', 'our' => 6],
            [3 => 'Полиграфия', 'our' => 2],
            [3 => 'Версткаэлектронныхизданий', 'our' => 6],
            [3 => 'Допечатнаяподготовка', 'our' => 6],
            [3 => 'Полиграфическаяверстка', 'our' => 6],
            [3 => 'Программирование', 'our' => 3],
            [3 => '1С-программирование', 'our' => 3],
            [3 => 'QA(тестирование)', 'our' => 3],
            [3 => 'Базыданных', 'our' => 3],
            [3 => 'Веб-программирование', 'our' => 3],
            [3 => 'Встраиваемыесистемы', 'our' => 3],
            [3 => 'Защитаинформации', 'our' => 3],
            [3 => 'Интерактивныеприложения', 'our' => 3],
            [3 => 'Плагины/Сценарии/Утилиты', 'our' => 3],
            [3 => 'Прикладноепрограммирование', 'our' => 3],
            [3 => 'Программированиеигр', 'our' => 3],
            [3 => 'Проектирование', 'our' => 3],
            [3 => 'РазработкаCRMиERP', 'our' => 3],
            [3 => 'Системноепрограммирование', 'our' => 3],
            [3 => 'Управлениепроектамиразработки', 'our' => 3],
            [3 => 'Flash/Flex-программирование', 'our' => 3],
            [3 => 'Созданиесайтов', 'our' => 1],
            [3 => 'Мобильныеверсиисайтов', 'our' => 1],
            [3 => 'Верстка', 'our' => 3],
            [3 => 'Доработкасайтов', 'our' => 1],
            [3 => 'Интернет-магазины', 'our' => 1],
            [3 => 'Сайт«подключ»', 'our' => 1],
            [3 => 'Флеш-сайты', 'our' => 1],
            [3 => 'Юзабилити-анализ', 'our' => 4],
            [3 => 'РекламаиМаркетинг', 'our' => 4],
            [3 => 'PR-менеджмент', 'our' => 4],
            [3 => 'SMM(маркетингвсоцсетях)', 'our' => 4],
            [3 => 'Бизнес-планы', 'our' => 8],
            [3 => 'Исследования', 'our' => 8],
            [3 => 'Исследованиярынкаиопросы', 'our' => 8],
            [3 => 'Креатив', 'our' => 8],
            [3 => 'Медиапланирование', 'our' => 8],
            [3 => 'Организациямероприятий', 'our' => 9],
            [3 => 'Продажиигенерациялидов', 'our' => 9],
            [3 => 'Рекламныеконцепции', 'our' => 9],
            [3 => 'Сбориобработкаинформации', 'our' => 9],
            [3 => 'Телемаркетингипродажипотелефону', 'our' => 9],
            [3 => 'Интернет-маркетинг', 'our' => 9],
            [3 => 'Сетииинформационныесистемы', 'our' => 9],
            [3 => 'Системныйадминистратор', 'our' => 7],
            [3 => 'ERPиCRMинтеграции', 'our' => 7],
            [3 => 'Администрированиебазданных', 'our' => 7],
            [3 => 'Сетевоеадминистрирование', 'our' => 7],
            [3 => 'Тексты', 'our' => 6],
            [3 => 'Контент-менеджер', 'our' => 6],
            [3 => 'Копирайтинг', 'our' => 6],
            [3 => 'Новости/Пресс-релизы', 'our' => 6],
            [3 => 'Постинг', 'our' => 6],
            [3 => 'Расшифровкааудиоивидеозаписей', 'our' => 6],
            [3 => 'Редактирование/Корректура', 'our' => 6],
            [3 => 'Резюме', 'our' => 6],
            [3 => 'Рерайтинг', 'our' => 6],
            [3 => 'Слоганы/Нейминг', 'our' => 6],
            [3 => 'Статьи', 'our' => 6],
            [3 => 'Стихи/Поэмы/Эссе', 'our' => 6],
            [3 => 'Сценарии', 'our' => 6],
            [3 => 'ТЗ/Help/Мануал', 'our' => 6],
            [3 => 'Текстынаиностранныхязыках', 'our' => 6],
            [3 => 'Флеш', 'our' => 3],
            [3 => 'Flashбаннеры', 'our' => 2],
            [3 => 'Виртуальныетуры', 'our' => 1],
            [3 => 'Флеш-графика', 'our' => 2],
            [3 => 'Фотография', 'our' => 5],
            [3 => 'Мероприятия/Репортажи', 'our' => 5],
            [3 => 'Фото-модели', 'our' => 5],
            [3 => 'Промышленнаяфотосъемка', 'our' => 5],
            [3 => 'Рекламная/Постановочнаяфотосъемка', 'our' => 5],
            [3 => 'Ретуширование/Коллажи', 'our' => 5],
            [3 => 'Свадебнаяфотосъемка', 'our' => 5],
            [3 => 'Художественная/Артфотосъемка', 'our' => 5],
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


    /**
     * @return array
     */
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