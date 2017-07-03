<?php

namespace frontend\controllers;

use backend\components\SphinxClient;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;

class ListController extends \yii\web\Controller
{
    public $layout = 'list';

    public function actionIndex()
    {

        if ($id = \Yii::$app->request->get('id')) {
            $parent = GoodsCategory::findOne(['id' => $id]);//得到当前id对应的分类记录
            $ids[]=$id;
            $models=[];
            $data = GoodsCategory::find()->where(['tree' => $parent->tree])->andWhere(['>', 'lft', $parent->lft])->andWhere(['<', 'rgt', $parent->rgt])->all();//得到子分类，同一树，左值小于，右值大于
            foreach ($data as $v){
                $ids[]=$v->id;//当前分类id，子分类的id存入数组中
            }
            foreach ($ids as $id){
                if($model=Goods::find()->where(['goods_category_id'=>$id])->all()){//在商品表中查询是否有此id对应的商品
                    $models[]=$model;//有则存入数组
                }
            }
        }else{
            $keyword= \Yii::$app->request->get('keyword');
            $query=Goods::find();
            $models=[];
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');//shopstore_search
//print_r($cl);
            if(!isset($res['matches'])){
//                throw new NotFoundHttpException('没有找到xxx商品');
                $models[]=$query->where(['id'=>0])->all();
            }else{

                //获取商品id
                //var_dump($res);exit;
                $ids = ArrayHelper::map($res['matches'],'id','id');
                $models[]=$query->where(['in','id',$ids])->all();

                $keywords = array_keys($res['words']);
                $options = array(
                    'before_match' => '<span style="color:red;">',
                    'after_match' => '</span>',
                    'chunk_separator' => '...',
                    'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
                );
//关键字高亮
                foreach ($models[0] as $index => $item) {

                    $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
                    $models[0][$index]->name = $name[0];
                }

            }
        }
        return $this->render('index',['models'=>$models]);
    }

}