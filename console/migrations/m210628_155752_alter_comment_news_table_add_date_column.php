<?php

use yii\db\Migration;

/**
 * Class m210628_155752_alter_comment_news_table_add_date_column
 */
class m210628_155752_alter_comment_news_table_add_date_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('comment_news', 'created_at', $this->string(255));
        $this->addColumn('comment_news', 'updated_at', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('comment_news', 'created_at');
        $this->dropColumn('comment_news', 'updated_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210628_155752_alter_comment_news_table_add_date_column cannot be reverted.\n";

        return false;
    }
    */
}
