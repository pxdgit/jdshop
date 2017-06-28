<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $amount
 * @property integer $member_id
 */
class Num extends Model
{
    /**
     * @inheritdoc
     */
    public $num0;
    public $num1;
    public $num2;
    public $num3;

    public function rules()
    {
        return [
            [['num0', 'num1', 'num2', 'num3'], 'required'],
        ];
    }
}
