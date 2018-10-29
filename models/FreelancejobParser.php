<?php
class Freelancejob extends Parser
{
    public $domain = 'https://www.freelancejob.ru';
    public $anchor = '.x17';
    public $targets = [
        '/projects/'
    ];
    public $update_time = 200; // сек
    public function parsePublished($published_string)
    {
        $str = "Проект добавлен: ";
        $pos = stripos($published_string, $str);
        if($pos === false) return false;
        $date_str = substr($published_string, $pos + strlen($str), 19);
        $date_str = str_replace(" в", '', $date_str);
        $date = date_create_from_format("d.m.Y H:i", $date_str);
        if($date === false)
        {
            return false;
        }
        return $date->getTimestamp();
    }
    public function parseItem($anchor)
    {
        $link = pq($anchor)->find('a.big');
        $href = $link->attr('href');
        $title = $link->html();
        $published_string = pq($anchor)->find('.x20')->html();
        $published = $this->parsePublished($published_string);
        if($published === false)
        {
            return false;
        }
        return [
            'domain' => 'freelancejob',
            'url' => $href,
            'title' => $title,
            'published' => $published,
            'founded' => date("Y-m-d H:i:s", time() ),
            'description' => ''
        ];
    }
}