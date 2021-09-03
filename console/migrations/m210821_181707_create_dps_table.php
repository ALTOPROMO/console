<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dps}}`.
 */
class m210821_181707_create_dps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dps}}', [
            'id' => $this->primaryKey(),
            'latitude' => $this->string(), // широта
            'longitude' => $this->string(), // долгота
            'comment' => $this->text(),
            'time' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dps}}');
    }
}
