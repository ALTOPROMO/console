<?php

namespace console\adapters\news;

use PhpQuery\PhpQuery as phpQuery;

PhpQuery::use_function(__NAMESPACE__);

/**
 * Парсел для сайта Альметьевской городской прокуратуры https://almprok.ru/
 */
class AlmetAdapter
{   
    public static function parse()
    {
        $page = file_get_contents('https://almetyevsk.tatarstan.ru/index.htm/news/');
        
        $doc_detail = phpQuery::newDocument($page);

        $links = pq($doc_detail)->find('div.list .list__item .list__title');

        $news = [];
        $id = 0;
        foreach ($links as $item) {
        	$item = pq($item);

        	$news[$id]['source'] = "https://almetyevsk.tatarstan.ru" . $item->find('a')->attr('href');
            $news[$id]['name'] = $item->find('a')->html();

            // Работаем с детальной
            $raw_detail = file_get_contents($news[$id]['source']);
            $doc_detail = phpQuery::newDocument($raw_detail);

            $news[$id]['image'] = $doc_detail->find('div.article__photo-photo img')->attr('src');

            $p = $doc_detail->find('div.article p');

            $news[$id]['text'] = "";
            foreach ($p as $p_current) {
                $p_current = pq($p_current);

                $news[$id]['text'] .= "<p>" . $p_current->html() . "</p>";
            }

            $id++;
        }

        return $news;
    }
}