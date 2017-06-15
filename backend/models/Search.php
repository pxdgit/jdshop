<?php

namespace app\models;

use backend\models\Brand;
use Yii;
use yii\db\ActiveQuery;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property integer $goods_category_id
 * @property integer $brand_id
 */
class Search extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
//    public static $goods_cate_name;
//    public static $brand_name;
    public static function tableName()
    {
        return 'goods';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id','name','brand_id','sn'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

        ];
    }

}
