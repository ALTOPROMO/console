<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ads}}`.
 */
class m201102_172412_create_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ads}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'alias' => $this->string(),
            'description' => $this->string(),
            'text' => $this->text(),
        ]);

        Yii::$app->db->createCommand()->batchInsert('ads', ['id', 'name', 'alias', 'description'], [
            [1, 'Растяжка над меню', 'main_banner', 'Огромная растяжка над меню на всех страницах сайта.'],
            [2, 'Главный баннер №1', 'main_banner_1', 'Первый баннер на всех страницах сбоку.'],
            [3, 'Главный баннер №2', 'main_banner_2', 'Второй баннер на всех страницах сбоку.'],
            [4, 'Блок с рекламой в новостях под заголовком', 'news_block_1', 'Данный блок отображается на детальной новостей под заголовком.'],
            [5, 'Блок с рекомендациями в новостях под новостью', 'news_block_2', 'Данный блок отображается на детальной новостей под новостью, выше комментариев.'],
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ads}}');
    }
}
