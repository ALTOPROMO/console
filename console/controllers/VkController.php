<?php

namespace console\controllers;

use yii\console\Controller;

use common\services\VkService;
use common\services\NewsService;

class VkController extends Controller
{
    public function actionSendPopular()
    {
        $news = NewsService::getOneViewed();

        if(!$news) {
            exit("На сегодня нет популярных новостей");
        }

        $publics = VkService::getPublics();

        $counter = 0;
        foreach($publics as $public) {
            $result = VkService::sendNews($news, $public->public_id);
            $counter++;
        }

        if($result) {
            echo "Пост(ы) успешно опубликован. Всего опубликовали " . $counter . " постов.\r\n";
        } else {
            echo "При публикации поста(ов) произошла ошибка.\r\n";
        }
    }
}