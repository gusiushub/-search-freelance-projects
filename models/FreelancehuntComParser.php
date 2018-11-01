<?php

namespace app\models;

//use app\models\FhApiSettings;
//use app\models\Projects;
use yii\base\Model,
    app\components\helpers\FunctionHelper;
//    app\models\Parser;


class FreelancehuntComParser extends Model
{
    /**
     * @return mixed
     */
    public function getProjectsByApi()
    {
        //$keys = FhApiSettings::findOne(1);
        $api_token = '3990846900405172';//$keys->api_token; // ваш идентификатор
        $api_secret = '81516950a1f2439964a1906964e8e127376f0071';// $keys->secret_key; // ваш секретный ключ
        $url = "https://api.freelancehunt.com/projects?per_page=50";
        $method = "GET";
        $signature = $this->sign($api_secret, $url, $method); // реализацию функции смотрите выше
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERPWD => $api_token . ":" . $signature,
            CURLOPT_URL => $url
        ]);
        $return = curl_exec($curl);
        curl_close($curl);
        return json_decode($return, true);
    }


    /**
     *
     */
    public function parseProjects(){
        $projectsByApi = $this->getProjectsByApi();
        //var_dump($projectsByApi);exit;
        foreach ($projectsByApi as $projectByApi) {
            if ($find = Parser::findOne(['source' => 'freelancehunt.com', 'projectId' => $projectByApi['project_id']])){
                continue;
            }
            $parser = new Parser([
                'source' => 'freelancehunt.com',
                'url' => $projectByApi['url'],
                'projectId' => $projectByApi['project_id'],
                'parse' => Parser::PARSE_SUCCESS,
                'view' => Parser::PARSE_SUCCESS,
                'text' => $projectByApi['description'],
                'budget' => isset($projectByApi['budget_amount']) ? $projectByApi['budget_amount'] : 1,
                'currency' => isset($projectByApi['budget_currency_code']) ? $projectByApi['budget_currency_code'] : 'UAH',
                'published' => date('Y-m-d H:i:s', strtotime($projectByApi['publication_time'])),
                'added' => date('Y-m-d H:i:s'),
                'name' => $projectByApi['name'],
            ]);
            if ($parser->save()) {
                $projectFL = new Projects([
                    'source' => $parser->id,
                    'url' => mb_strtolower(FunctionHelper::cyrillicToLatin($parser->name) . '-' . $parser->projectId),
                    'number' => $parser->projectId,
                    'create' => $parser->added,
                    'application_date' => $parser->added,
                    'active' => 1,
                    'activeTo' => date('Y-m-d H:i:s', strtotime($projectByApi['expire_time'])),
                    'published' => 1,
                    'name' => $parser->name,
                    'budget' => $parser->budget,
                    'currency' => $parser->currency,
                    'generalDescription' => $parser->text,
                    'categoryId' => $parser->category,
                ]);
                $projectFL->save();
            }
        }
    }
    /**
     * @param $api_secret
     * @param $url
     * @param $method
     * @param string $post_params
     * @return string
     */
    public function sign($api_secret, $url, $method, $post_params = '')
    {
        return base64_encode(hash_hmac("sha256", $url . $method . $post_params, $api_secret, true));
    }
    public function getCategory($skill){
        return 3;
        /*
         *
         Array ( [0] => Array ( [skill_id] => 56 [skill_name] => 1C ) [1] => Array ( [skill_id] => 59 [skill_name] => 3D графика ) [2] => Array ( [skill_id] => 182 [skill_name] => Blockchain ) [3] => Array ( [skill_id] => 24 [skill_name] => C# ) [4] => Array ( [skill_id] => 2 [skill_name] => C/C++ ) [5] => Array ( [skill_id] => 177 [skill_name] => Delphi/Object Pascal ) [6] => Array ( [skill_id] => 181 [skill_name] => DevOps ) [7] => Array ( [skill_id] => 136 [skill_name] => E-mail маркетинг ) [8] => Array ( [skill_id] => 5 [skill_name] => Flash/Flex ) [9] => Array ( [skill_id] => 48 [skill_name] => FreeBSD ) [10] => Array ( [skill_id] => 54 [skill_name] => Git/Mercurial ) [11] => Array ( [skill_id] => 173 [skill_name] => Go ) [12] => Array ( [skill_id] => 124 [skill_name] => HTML/CSS верстка ) [13] => Array ( [skill_id] => 62 [skill_name] => IP-телефония/VoIP ) [14] => Array ( [skill_id] => 13 [skill_name] => Java ) [15] => Array ( [skill_id] => 28 [skill_name] => Javascript ) [16] => Array ( [skill_id] => 6 [skill_name] => Linux/Unix ) [17] => Array ( [skill_id] => 146 [skill_name] => Mac OS/Objective C ) [18] => Array ( [skill_id] => 61 [skill_name] => Microsoft .NET ) [19] => Array ( [skill_id] => 174 [skill_name] => Node.js ) [20] => Array ( [skill_id] => 1 [skill_name] => PHP ) [21] => Array ( [skill_id] => 22 [skill_name] => Python ) [22] => Array ( [skill_id] => 23 [skill_name] => Ruby ) [23] => Array ( [skill_id] => 134 [skill_name] => SEO-аудит сайтов ) [24] => Array ( [skill_id] => 160 [skill_name] => Swift ) [25] => Array ( [skill_id] => 7 [skill_name] => Windows ) [26] => Array ( [skill_id] => 39 [skill_name] => Администрирование систем ) [27] => Array ( [skill_id] => 79 [skill_name] => Английский язык ) [28] => Array ( [skill_id] => 91 [skill_name] => Анимация ) [29] => Array ( [skill_id] => 108 [skill_name] => Архитектурные проекты ) [30] => Array ( [skill_id] => 113 [skill_name] => Аудио/видео монтаж ) [31] => Array ( [skill_id] => 86 [skill_name] => Базы данных ) [32] => Array ( [skill_id] => 41 [skill_name] => Баннеры ) [33] => Array ( [skill_id] => 112 [skill_name] => Бизнес-консультирование ) [34] => Array ( [skill_id] => 149 [skill_name] => Бухгалтерские услуги ) [35] => Array ( [skill_id] => 99 [skill_name] => Веб-программирование ) [36] => Array ( [skill_id] => 58 [skill_name] => Векторная графика ) [37] => Array ( [skill_id] => 144 [skill_name] => Видеореклама ) [38] => Array ( [skill_id] => 161 [skill_name] => Видеосъемка ) [39] => Array ( [skill_id] => 111 [skill_name] => Визуализация и моделирование ) [40] => Array ( [skill_id] => 176 [skill_name] => Встраиваемые системы и микроконтроллеры ) [41] => Array ( [skill_id] => 115 [skill_name] => Геоинформационные системы ) [42] => Array ( [skill_id] => 156 [skill_name] => Дизайн визиток ) [43] => Array ( [skill_id] => 132 [skill_name] => Дизайн выставочных стендов ) [44] => Array ( [skill_id] => 42 [skill_name] => Дизайн интерфейсов ) [45] => Array ( [skill_id] => 106 [skill_name] => Дизайн интерьеров ) [46] => Array ( [skill_id] => 179 [skill_name] => Дизайн мобильных приложений ) [47] => Array ( [skill_id] => 43 [skill_name] => Дизайн сайтов ) [48] => Array ( [skill_id] => 117 [skill_name] => Дизайн упаковки ) [49] => Array ( [skill_id] => 141 [skill_name] => Живопись и графика ) [50] => Array ( [skill_id] => 65 [skill_name] => Защита ПО и безопасность ) [51] => Array ( [skill_id] => 166 [skill_name] => Иврит ) [52] => Array ( [skill_id] => 93 [skill_name] => Иконки и пиксельная графика ) [53] => Array ( [skill_id] => 90 [skill_name] => Иллюстрации и рисунки ) [54] => Array ( [skill_id] => 148 [skill_name] => Инжиниринг ) [55] => Array ( [skill_id] => 129 [skill_name] => Интеграция платежных систем ) [56] => Array ( [skill_id] => 68 [skill_name] => Интернет-магазины и электронная коммерция ) [57] => Array ( [skill_id] => 172 [skill_name] => Инфографика ) [58] => Array ( [skill_id] => 84 [skill_name] => Испанский язык ) [59] => Array ( [skill_id] => 82 [skill_name] => Итальянский язык ) [60] => Array ( [skill_id] => 72 [skill_name] => Компьютерные сети ) [61] => Array ( [skill_id] => 154 [skill_name] => Консалтинг ) [62] => Array ( [skill_id] => 127 [skill_name] => Контекстная реклама ) [63] => Array ( [skill_id] => 104 [skill_name] => Контент-менеджер ) [64] => Array ( [skill_id] => 76 [skill_name] => Копирайтинг ) [65] => Array ( [skill_id] => 107 [skill_name] => Ландшафтный дизайн ) [66] => Array ( [skill_id] => 17 [skill_name] => Логотипы ) [67] => Array ( [skill_id] => 157 [skill_name] => Локализация ПО, сайтов и игр ) [68] => Array ( [skill_id] => 94 [skill_name] => Маркетинговые исследования ) [69] => Array ( [skill_id] => 175 [skill_name] => Машинное обучение ) [70] => Array ( [skill_id] => 100 [skill_name] => Музыка ) [71] => Array ( [skill_id] => 105 [skill_name] => Набор текстов ) [72] => Array ( [skill_id] => 38 [skill_name] => Написание статей ) [73] => Array ( [skill_id] => 163 [skill_name] => Написание сценария ) [74] => Array ( [skill_id] => 109 [skill_name] => Наружная реклама ) [75] => Array ( [skill_id] => 83 [skill_name] => Настройка ПО/серверов ) [76] => Array ( [skill_id] => 123 [skill_name] => Нейминг и слоганы ) [77] => Array ( [skill_id] => 80 [skill_name] => Немецкий язык ) [78] => Array ( [skill_id] => 102 [skill_name] => Обработка аудио ) [79] => Array ( [skill_id] => 101 [skill_name] => Обработка видео ) [80] => Array ( [skill_id] => 178 [skill_name] => Обработка данных ) [81] => Array ( [skill_id] => 18 [skill_name] => Обработка фото ) [82] => Array ( [skill_id] => 95 [skill_name] => Обучение ) [83] => Array ( [skill_id] => 151 [skill_name] => Оформление страниц в социальных сетях ) [84] => Array ( [skill_id] => 169 [skill_name] => Парсинг данных ) [85] => Array ( [skill_id] => 37 [skill_name] => Перевод текстов ) [86] => Array ( [skill_id] => 170 [skill_name] => Поиск и сбор информации ) [87] => Array ( [skill_id] => 14 [skill_name] => Поисковое продвижение (SEO) ) [88] => Array ( [skill_id] => 135 [skill_name] => Поисковое управление репутацией (SERM) ) [89] => Array ( [skill_id] => 75 [skill_name] => Полиграфический дизайн ) [90] => Array ( [skill_id] => 164 [skill_name] => Предметный дизайн ) [91] => Array ( [skill_id] => 103 [skill_name] => Прикладное программирование ) [92] => Array ( [skill_id] => 162 [skill_name] => Продажи и генерация лидов ) [93] => Array ( [skill_id] => 131 [skill_name] => Продвижение в социальных сетях (SMM) ) [94] => Array ( [skill_id] => 64 [skill_name] => Проектирование ) [95] => Array ( [skill_id] => 165 [skill_name] => Прототипирование ) [96] => Array ( [skill_id] => 138 [skill_name] => Публикация объявлений ) [97] => Array ( [skill_id] => 171 [skill_name] => Работа с клиентами ) [98] => Array ( [skill_id] => 88 [skill_name] => Разработка игр ) [99] => Array ( [skill_id] => 121 [skill_name] => Разработка под Android ) [100] => Array ( [skill_id] => 120 [skill_name] => Разработка под iOS (iPhone/iPad) ) [101] => Array ( [skill_id] => 114 [skill_name] => Разработка презентаций ) [102] => Array ( [skill_id] => 180 [skill_name] => Разработка чат-ботов ) [103] => Array ( [skill_id] => 152 [skill_name] => Разработка шрифтов ) [104] => Array ( [skill_id] => 168 [skill_name] => Редактура и корректура текстов ) [105] => Array ( [skill_id] => 133 [skill_name] => Реклама в социальных медиа ) [106] => Array ( [skill_id] => 159 [skill_name] => Рекрутинг ) [107] => Array ( [skill_id] => 125 [skill_name] => Рерайтинг ) [108] => Array ( [skill_id] => 116 [skill_name] => Рефераты, дипломы, курсовые ) [109] => Array ( [skill_id] => 142 [skill_name] => Рукоделие/Hand made ) [110] => Array ( [skill_id] => 85 [skill_name] => Системное программирование ) [111] => Array ( [skill_id] => 96 [skill_name] => Создание сайта под ключ ) [112] => Array ( [skill_id] => 45 [skill_name] => Сопровождение сайтов ) [113] => Array ( [skill_id] => 140 [skill_name] => Стихи, песни, проза ) [114] => Array ( [skill_id] => 57 [skill_name] => Тестирование и QA ) [115] => Array ( [skill_id] => 97 [skill_name] => Техническая документация ) [116] => Array ( [skill_id] => 145 [skill_name] => Тизерная реклама ) [117] => Array ( [skill_id] => 122 [skill_name] => Транскрибация ) [118] => Array ( [skill_id] => 150 [skill_name] => Управление клиентами/CRM ) [119] => Array ( [skill_id] => 89 [skill_name] => Управление проектами ) [120] => Array ( [skill_id] => 143 [skill_name] => Услуги диктора ) [121] => Array ( [skill_id] => 78 [skill_name] => Установка и настройка CMS ) [122] => Array ( [skill_id] => 77 [skill_name] => Фирменный стиль ) [123] => Array ( [skill_id] => 139 [skill_name] => Фотосъемка ) [124] => Array ( [skill_id] => 158 [skill_name] => Французский язык ) [125] => Array ( [skill_id] => 147 [skill_name] => Чертежи и схемы ) [126] => Array ( [skill_id] => 153 [skill_name] => Юридические услуги ) )
         *
         * */
    }
}