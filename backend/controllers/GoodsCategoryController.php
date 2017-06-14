<?php

namespace backend\controllers;

use app\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex(){
        //按tree排序是将不同一级分类分割开；按左值是按遍历顺序排序，可以顺序列出分类及子分类
        $models=GoodsCategory::find()->orderBy('tree,lft')->all();
        return $this->render('index',['models'=>$models]);
    }
    public function actionAdd(){
        $model=new GoodsCategory();
//        $option=ArrayHelper::map(GoodsCategory::find(),'id','name');
        $goodscates=ArrayHelper::merge([['id'=>'0','name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());//通过数组合并添加一个顶级分类
//        $option=ArrayHelper::map($goodscates,'id','name');
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
                if($model->parent_id){//添加非一级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);//查找id等于传过来的parent_id的分类
                    $model->prependTo($parent);
                }else{//添加一级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }
        return $this->render('add',['model'=>$model,'option'=>$goodscates]);
    }
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $old_parent=$model->parent_id;
        if($model==null){
            throw new NotFoundHttpException('没有此分类');
        }
        $goodscates=ArrayHelper::merge([['id'=>'0','name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->parent_id){//非0
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{//为0
                if($old_parent){//插件本身的bug，需要判断下，是否是一级分类，一级分类0用save方法
                    $model->makeRoot();
                }else{
                    $model->save();
                }
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods-category/index']);
        }
        return $this->render('add',['model'=>$model,'option'=>$goodscates]);
    }
    public function actionTest(){
        //顶级分类
//        $goodscategory=new GoodsCategory();
//        $goodscategory->name="家用电器";
//        $goodscategory->parent_id=0;
//        $goodscategory->makeRoot();
        //二级分类
//        $goodscategory=new GoodsCategory();
//        $parent=GoodsCategory::findOne(['id'=>1]);
//        $goodscategory->name='小家电';
//        $goodscategory->parent_id=$parent->id;
//        $goodscategory->prependTo($parent);
        $goodscategory=GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree',['goodscategory'=>$goodscategory]);//不加载布局文件


    }

}
