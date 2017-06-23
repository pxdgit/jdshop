<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_images".
 *
 * @property integer $id
 * @property string $img
 * @property integer $goods_id
 */
class GoodsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgfiles;
    public static function tableName()
    {
        return 'goods_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id'], 'required'],
            [['imgfiles'], 'file', 'extensions' =>['jpg','png','bmp'],'maxFiles'=>10],
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
