<?php

namespace console\controllers;

use common\models\NewsCategory;
use Yii;
use yii\console\Controller;
use common\models\News;
use Phpml\ModelManager;

// Адаптеры для новостей
use console\adapters\news\AlmprokAdapter;
use console\adapters\news\AlmetAdapter;

class NewsController extends Controller
{   
    public function actionIndex()
    {
        $almprok = AlmprokAdapter::parse();
        $almet = AlmetAdapter::parse();

        $news = array_merge(
        	$almprok,
            $almet
        );

        $counter = 0;
        foreach ($news as $item) {
            // TODO: вынести функционал в сервис NewsService
        	if(!News::find()->where(['name' => $item['name']])->one()) {
        		$el = new News();

        		$el->name = $item['name'];

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

               /* $item = NewsService::getById($el->id);
                $main_public = VkService::getMainPublic();
                $result_vk_send = VkService::sendNews($item, $main_public->public_id);

                if($result_vk_send) {
                    echo "Добавили новость в основное сообщество.";
                }*/

        		$counter++;
        	}
        }

        echo "Готово, всего спарсили " . $counter . " новостей.";
    }
}