<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vk_public}}`.
 */
class m201028_205746_create_vk_public_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vk_public}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'url' => $this->string(),
            'public_id' => $this->string(),
            'is_main' => $this->integer(),
            'active' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vk_public}}');
    }
}
