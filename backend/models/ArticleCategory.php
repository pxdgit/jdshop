<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $is_help
 * @property integer $status
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static  $allhelp=['0'=>'非帮助文档','1'=>'帮助文档'];
    public static  $allstatus=[-1=>'删除','0'=>'隐藏',1=>'正常'];
//    public function getarticlecategory(){
//        return $this->hasMany(Article::className(),['article_category_id'=>'id']);
//    }
    public function getarticle(){
        return $this->hasMany(Article::className(),['article_category_id'=>'id']);
    }
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'is_help', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'sort' => '排序',
            'is_help' => '类型',
            'status' => '状态',
        ];
    }
}
