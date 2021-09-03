<?php

namespace console\adapters\news;

use PhpQuery\PhpQuery as phpQuery;

PhpQuery::use_function(__NAMESPACE__);

/**
 * Парсер для сайта Альметьевской городской прокуратуры https://almprok.ru/
 */
class AlmprokAdapter
{   
    /**
     * Метод парсит сайт https://almprok.ru/.
     * 
     * @return array
     */
    public static function parse()
    {
        $page = file_get_contents('https://almprok.ru/');
        
        $doc_detail = phpQuery::newDocument($page);

        $links = pq($doc_detail)->find('div.news_main_page a');

        $news = [];
        $id = 0;
        foreach ($links as $item) {
            $item = pq($item);

            $news[$id]['source'] = $item->attr('href');
            $news[$id]['name'] = $item->find('p')->html();

            // Работаем с детальной страницей новости.
            $raw_detail = file_get_contents($news[$id]['source']);
            $doc_detail = phpQuery::newDocument($raw_detail);

            $news[$id]['text'] = $doc_detail->find('div.main_text_block div.main_text_text p')->html();

            $id++;
        }

        return $news;
    }
}
