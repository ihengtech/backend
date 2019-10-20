<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file_manage}}`.
 */
class m190907_032852_create_file_manage_table extends Migration
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
        $this->createTable('{{%file_manage}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'device_id' => $this->integer()->notNull()->defaultValue(0)->comment('设备 ID'),
            'raw_name' => $this->string(255)->notNull()->comment('图像原始名称'),
            'unique_name' => $this->string(255)->notNull()->comment('图像原始名称'),
            'detail' => 'mediumblob DEFAULT NULL COMMENT "DEVICE CONFIG"',
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'updated_at' => $this->timestamp()->null()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('device_id_index', '{{%file_manage}}', ['device_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_032852_create_file_manage_table forbidden!";
        //$this->dropTable('{{%file_manage}}');
    }
}
