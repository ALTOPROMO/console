<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\Film;
use PhpQuery\PhpQuery as phpQuery;

PhpQuery::use_function(__NAMESPACE__);

/**
 * Класс парсит сайт с афишей https://e-almet.ru 
 */
class FilmController extends Controller
{
    /**
     * Метод парсит раздел сайта https://e-almet.ru/afisha/cinema/
     * 
     * @return null
     */
    public function actionIndex()
    {
        $page = file_get_contents('https://e-almet.ru/afisha/cinema/');

        $doc = phpQuery::newDocument($page);

        // Список фильмов
        $list = pq($doc)->find('table.sv_tabl:eq(0) tr:gt(1)');

        $films = [];
        $id = 0;
        foreach ($list as $film) {
            $film = pq($film);

            // Название фильма
            $films[$id]['name'] = $film->find('td.zf2 a')->html();

            // Жанр
            $films[$id]['genre'] = $film->find('td.zf2 span')->html();

            // Сеансы
            $films[$id]['sessions'] = $film->find('td:eq(2)')->html();

            $doc_detail_link = $film->find('td.zf2 a')->attr("href");

            $page_detail = file_get_contents($doc_detail_link);
            $doc_detail = phpQuery::newDocument($page_detail);

            $concrete_film = pq($doc_detail);

            $table_detail = $concrete_film->find('#mtx table tr');

            // Постер фильма
            $films[$id]['image'] = "http://e-almet.ru/" . $table_detail->find('td:eq(0) img')->attr('src');

            $characters = $table_detail->find('td:eq(1) div.pd');

            foreach ($characters as $character) {
            	$character = pq($character);

            	// Характеристики фильма
            	$films[$id]['characters'][] = [
            	    'name' => $character->find('span strong')->html(),
                    'value' => trim($character->contents()->eq(1)->text())
                ];
            }

            $dates = explode("–", $concrete_film->find('table.sv_tabl tr td.place')->html());

            // Старт в прокате
            $date_start = date_parse_from_format('d.m.Y', trim($dates[0]).date(".Y"));
            $timestamp_start = mktime(0, 0, 0, $date_start['month'], $date_start['day'], $date_start['year']);

            // Окончание в прокате
            $date_end = date_parse_from_format('d.m.Y', $dates[0].date(".Y"));
            $timestamp_end = mktime(0, 0, 0, $date_end['month'], $date_end['day'], $date_end['year']);

            // Масссив с датой начала и окончания проката в формате timest
            $films[$id]['date'] = [
                'start' => $timestamp_start,
                'end' => $timestamp_end
            ];

        	$id++;
        }

        // Теперь сохраняем фильм в базе данных
        foreach ($films as $film) {
            $haveFilm = Film::find()->where(['name' => $film['name']])->one();

            if(!$haveFilm) {
                $modelFilm = new Film();

                $modelFilm->name = $film['name'];
                $modelFilm->genre = $film['genre'];
                $modelFilm->sessions = $film['sessions'];
                $modelFilm->image = $film['image'];

                $modelFilm->save();
            }
        }
    }
}
