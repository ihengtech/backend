<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%face}}`.
 */
class m190907_034519_create_face_detect_table extends Migration
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
        $this->createTable('{{%face_detect}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'device_id' => $this->integer()->notNull()->defaultValue(0)->comment("设备 ID"),
            'file_manage_id' => $this->integer()->notNull()->defaultValue(0)->comment("文件 ID"),
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'analysis_result' => 'mediumblob DEFAULT NULL COMMENT "ANALYSIS RESULT"',
        ], $tableOptions);

        $this->createIndex('device_id_index', '{{%face_detect}}', ['device_id']);
        $this->createIndex('file_manage_id_index', '{{%face_detect}}', ['file_manage_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_034519_create_face_detect_table forbidden!";
        //$this->dropTable('{{%face}}');
    }
}
