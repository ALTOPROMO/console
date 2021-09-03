<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%film}}`.
 */
class m201111_142855_create_film_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%film}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'genre' => $this->string(),
            'sessions' => $this->string(),
            'image' => $this->string(),
            'timing' => $this->string(),
            'from_country' => $this->string(),
            'producer' => $this->string(),
            'roles' => $this->string(),
            'description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%film}}');
    }
}
