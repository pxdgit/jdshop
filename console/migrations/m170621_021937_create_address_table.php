<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_021937_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->comment('用户id'),
            'addressee'=>$this->string()->notNull()->comment('收货人'),
            'province'=>$this->integer()->comment('省'),
            'city'=>$this->integer()->comment('市'),
            'area'=>$this->integer()->comment('地区'),
            'address'=>$this->string()->comment('详细收件地址'),
            'tel'=>$this->string(11)->comment('手机号码'),
            'status'=>$this->integer(1)->comment('状态')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
