<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_category}}`.
 */
class m201028_154434_create_news_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(),
        ]);

        Yii::$app->db->createCommand()->batchInsert('news_category', ['name', 'code'], [
            ['Общество', 'social'],
            ['Экономика', 'economic'],
            ['Политика', 'politic'],
            ['Спорт', 'sport'],
            ['Культура', 'culture'],
            ['ДТП', 'dtp'],
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_category}}');
    }
}
