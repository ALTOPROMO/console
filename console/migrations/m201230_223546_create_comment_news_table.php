<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment_news}}`.
 */
class m201230_223546_create_comment_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment_news}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(),
            'user_id' => $this->integer(),
            'news_id' => $this->integer(),
            'text' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comment_news}}');
    }
}
