<?php

namespace console\controllers;

use Yii;
use common\models\NewsCategory;
use yii\console\Controller;
use common\models\News;
use Phpml\ModelManager;
use console\adapters\news\AlmprokAdapter;
use console\adapters\news\AlmetAdapter;

/**
 * Класс управляет "сырыми" новостями.
 */
class NewsController extends Controller
{   
    /**
     * Данный метод используется для получения списков новостей из адаптеров и его сохранения в базе данных. Вызывается по cron.
     * 
     * @return null
     */
    public function actionIndex()
    {
        $almprok = AlmprokAdapter::parse();
        $almet = AlmetAdapter::parse();

        // Объединяем массивы из адаптеров
        $news = array_merge(
            $almprok,
            $almet
        );

        $counter = 0;
        foreach ($news as $item) {
            if(!News::find()->where(['name' => $item['name']])->one()) {
                $el = new News();

                $el->name = $item['name'];

                // Если у новости есть изображение, скачиваем и сохраняем его
                if(isset($item['image']) && $item['image'] != null) {
                    $image = $item['image'];

                    $dir = Yii::getAlias("@news_images");

                    $path_to_image = md5($image . time()) . "." . strtolower(pathinfo($image, PATHINFO_EXTENSION));

                    $image = file_put_contents(
                        $dir . "/" . $path_to_image,
                        file_get_contents($image)
                    );

                    if($image) {
                        $image = $path_to_image;
                    } else {
                        $image = null;
                    }
                } else {
                    $image = null;
                }

                // Предсказываем категорию
                $modelManager = new ModelManager();
                $ai_path = Yii::getAlias("@ai");
                $restoredClassifier = $modelManager->restoreFromFile($ai_path . "/model/model.ai");
                $category_code = $restoredClassifier->predict([$el->name]);

                // Пытаемся получить категорию по предсказанному коду (поле CODE в таблице с категориями)
                $category = NewsCategory::find()->where(['code' => $category_code])->one();

                $el->category_id = $category ? $category->id : '';
                $el->views = 0;
                $el->image = $image;
                $el->text = $item['text'];
                $el->source = $item['source'];
                $el->created_at = time();
                $el->updated_at = time();

                $el->save();

                $counter++;
            }
        }

        echo "Готово, всего спарсили " . $counter . " новостей.";
    }
}
