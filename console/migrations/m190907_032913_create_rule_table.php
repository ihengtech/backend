<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rule}}`.
 */
class m190907_032913_create_rule_table extends Migration
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
        $this->createTable('{{%rule}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'uid' => $this->integer()->notNull()->defaultValue(0)->comment("UID"),
            'device_id' => $this->integer()->notNull()->defaultValue(0)->comment("DEVICE ID"),
            'rule_key' => $this->string(50)->notNull()->comment('条件名称'),
            'rule_condition' => $this->integer()->notNull()->comment('条件'),
            'rule_value' => $this->string(255)->notNull()->comment('条件值'),
            'notice' => $this->string(255)->notNull()->comment('描述'),
            'status' => 'tinyint NOT NULL DEFAULT 0 COMMENT "状态"',
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'updated_at' => $this->timestamp()->null()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('uid_index', '{{%rule}}', ['uid']);
        $this->createIndex('device_id_index', '{{%rule}}', ['device_id']);
        $this->createIndex('notice_index', '{{%rule}}', ['notice']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_032913_create_rule_table forbidden!";
        //$this->dropTable('{{%rule}}');
    }
}
