<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%config}}`.
 */
class m190907_032836_create_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'uid' => $this->integer()->notNull()->defaultValue(0)->comment("UID"),
            'config_key' => $this->string(255)->notNull()->unique()->comment('CONFIG KEY'),
            'config_value' => 'mediumblob DEFAULT NULL COMMENT "CONFIG VALUE"',
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'updated_at' => $this->timestamp()->null()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('uid_index', '{{%config}}', ['uid']);
        $this->createIndex('config_key_index', '{{%config}}', ['config_key']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_032836_create_config_table forbidden!";
        //$this->dropTable('{{%config}}');
    }
}
