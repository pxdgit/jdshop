<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_album`.
 */
class m170613_104811_create_goods_album_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_album', [
            'id' => $this->primaryKey(),
            'url'=>$this->string(255)->comment('图片地址'),
            'goods_id'=>$this->integer(1)->comment('商品id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_album');
    }
}
