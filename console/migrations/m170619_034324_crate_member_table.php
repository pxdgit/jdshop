<?php

use yii\db\Migration;

class m170619_034324_crate_member_table extends Migration
{
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(100)->notNull()->comment('密码(密文)'),
            'email' => $this->string(100)->notNull()->unique()->comment('邮箱'),
            'tel'=>$this->string(11)->comment('电话'),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(10)->comment('状态'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
            'last_login_time' => $this->integer()->notNull()->comment('最后登录时间'),
            'last_login_ip' => $this->integer()->notNull()->comment('最后登录ip'),
        ]);
    }

    public function down()
    {
        echo "m170619_034324_crate_member_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
