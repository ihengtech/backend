<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190907_032819_create_user_table extends Migration
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
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'mobile_phone' => $this->string(20)->notNull()->unique()->comment('手机号码'),
            'we_chat_open_id' => $this->string(255)->null()->unique()->comment('微信 OPENID'),
            'username' => $this->string(255)->notNull()->unique()->comment('用户名'),
            'password_hash' => $this->string(255)->notNull()->comment('密码'),
            'access_token' => $this->string(255)->notNull()->unique()->comment('ACCESS_TOKEN'),
            'role' => $this->integer()->notNull()->defaultValue(0)->comment('角色'),
            'points' => $this->integer()->notNull()->defaultValue(0)->comment("积分"),
            'avatar_url' => $this->string(255)->null()->comment('头像'),
            'email' => $this->string(255)->null()->comment('电子邮件'),
            'auth_key' => $this->string(255)->null()->comment('验证KEY'),
            'access_token_expired_at' => $this->timestamp()->null()->comment('TOKEN 过期时间'),
            'password_reset_token' => $this->string(255)->null()->comment('密码重置TOKEN'),
            'last_login_at' => $this->timestamp()->null()->comment('最后登录时间'),
            'rate_limit' => $this->integer()->notNull()->defaultValue(0)->comment("速率限制"),
            'allowance' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('剩余请求次数'),
            'allowance_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('请求时间戳'),
            'status' => 'tinyint NOT NULL DEFAULT 0 COMMENT "状态"',
            'created_at' => $this->timestamp()->null()->comment('创建时间'),
            'updated_at' => $this->timestamp()->null()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('we_chat_open_id_index', '{{%user}}', ['we_chat_open_id']);
        $this->createIndex('mobile_phone_index', '{{%user}}', ['mobile_phone']);
        $this->createIndex('username_index', '{{%user}}', ['username']);
        $this->createIndex('access_token_index', '{{%user}}', ['access_token']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "safe down m190907_032819_create_user_table forbidden!";
        //$this->dropTable('{{%user}}');
    }
}
