<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m190907_050246_init_user
 */
class m190907_050246_init_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190907_050246_init_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190907_050246_init_user cannot be reverted.\n";

        return false;
    }
    */
}
