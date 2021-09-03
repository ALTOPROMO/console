<?php

use yii\db\Migration;

/**
 * Class m201017_220204_alter_table_news_change_type_created_at_and_updated_at
 */
class m201017_220204_alter_table_news_change_type_created_at_and_updated_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('news', 'created_at', $this->string(255));
        $this->alterColumn('news', 'updated_at', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201017_220204_alter_table_news_change_type_created_at_and_updated_at cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201017_220204_alter_table_news_change_type_created_at_and_updated_at cannot be reverted.\n";

        return false;
    }
    */
}
