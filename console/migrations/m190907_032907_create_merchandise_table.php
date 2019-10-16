<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%merchandise}}`.
 */
class m190907_032907_create_merchandise_table extends Migration
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
        $this->createTable('{{%merchandise}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'uid' => $this->integer()->notNull()->defaultValue(0)->comment("UID"),
            'name' => $this->string(50)->notNull()->comment('用户名'),
            'price' => $this->integer()->notNull()->defaultValue(0)->comment('原价'),
            'discount' => $this->integer()->notNull()->defaultValue(10)->comment('打折'),
            'image_url' => $this->string(255)->comment('商品图像'),
            'detail' => 'mediumblob DEFAULT NULL COMMENT "MERCHANDISE DETAIL"',
            'status' => 'tinyint NOT NULL DEFAULT 0 COMMENT "状态"',
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'updated_at' => $this->timestamp()->null()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('uid_index', '{{%merchandise}}', ['uid']);
        $this->createIndex('name_index', '{{%merchandise}}', ['name']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_032907_create_merchandise_table forbidden!";
        //$this->dropTable('{{%merchandise}}');
    }
}
