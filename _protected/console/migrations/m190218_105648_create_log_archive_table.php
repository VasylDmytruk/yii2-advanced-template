<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log_archive}}`.
 */
class m190218_105648_create_log_archive_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'CREATE TABLE log_archive LIKE log;';

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log_archive}}');
    }
}
