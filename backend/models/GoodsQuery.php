<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/11 0011
 * Time: 10:56
 */

namespace backend\models;


use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class GoodsQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}