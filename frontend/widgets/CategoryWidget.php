<?php

namespace frontend\widgets;
use backend\models\GoodsCategory;
use yii\base\Widget;

class CategoryWidget extends Widget
{
    public function init(){
        parent::init();
    }
    public function run(){
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $category_html=$redis->get('category_html');
        $redis->del('category_html');
        if($category_html==null){
            echo '数据库';
            $categories=GoodsCategory::findAll(['parent_id'=>0]);
            $category_html=$this->renderFile('@app/widgets/view/category.php',['firstcates'=>$categories]);
            $redis->set('category_html',$category_html,3600);//每七天更新一次分类列表
        }
//        return $category_html;
    }
}