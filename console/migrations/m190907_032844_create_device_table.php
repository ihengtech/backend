<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%device}}`.
 */
class m190907_032844_create_device_table extends Migration
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
        $this->createTable('{{%device}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'uid' => $this->integer()->notNull()->defaultValue(0)->comment("UID"),
            'serial_number' => $this->string(255)->notNull()->unique()->comment('SERIAL NUMBER'),
            'mac_address' => $this->string(255)->notNull()->unique()->comment('MAC ADDRESS'),
            'lng' => $this->double()->null()->comment('经度'),
            'lat' => $this->double()->null()->comment('纬度'),
            'device_config' => 'mediumblob DEFAULT NULL COMMENT "DEVICE CONFIG"',
            'status' => 'tinyint NOT NULL DEFAULT 0 COMMENT "状态"',
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'updated_at' => $this->timestamp()->null()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('uid_index', '{{%device}}', ['uid']);
        $this->createIndex('serial_number_index', '{{%device}}', ['serial_number']);
        $this->createIndex('mac_address_index', '{{%device}}', ['mac_address']);
        $this->createIndex('lng_index', '{{%device}}', ['lng']);
        $this->createIndex('lat_index', '{{%device}}', ['lat']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_032844_create_device_table forbidden!";
        //$this->dropTable('{{%device}}');
    }
}
