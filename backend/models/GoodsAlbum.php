<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_album".
 *
 * @property integer $id
 * @property string $img
 * @property integer $goods_id
 */
class GoodsAlbum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgfiles;
    public static function tableName()
    {
        return 'goods_album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id'], 'required'],
//            [['id', 'goods_id'], 'integer'],
            [['imgfiles'], 'file', 'extensions' =>['jpg','png','bmp']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => 'Img',
            'goods_id' => 'Goods ID',
        ];
    }
}
