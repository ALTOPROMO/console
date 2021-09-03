<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%organization}}`.
 */
class m201122_193134_create_organization_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%organization}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'name' => $this->string(),
            'description' => $this->text(),
            'phone' => $this->string(),
            'address' => $this->string(),
            'operating_mode' => $this->text(),
            'site_link' => $this->string(),
            'vk_link' => $this->string(),
            'instagram_link' => $this->string(),
            'facebook_link' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organization}}');
    }
}
