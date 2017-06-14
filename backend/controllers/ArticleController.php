<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Article::find()->orderBy(['id'=>SORT_DESC]);//排序(e.g. orderBy（['id' => SORT_ASC, 'name' => SORT_DESC])).
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>'1'
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    public function actionAdd(){
        $model=new Article();
        $detail=new ArticleDetail();
//        获取分类方法一：
        $cate=ArticleCategory::findAll(['status'=>1]);
        $cate=ArrayHelper::map($cate,'id','name');

//        获取分类方法二：
//        $cate=ArticleCategory::find()->asArray()->where(['status'=>1])->all();
//        $cate=ArrayHelper::map($cate,'id','name');

        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $detail->load($request->post());
            if($model->validate()&&$detail->validate()){
                $model->save();
                $detail->article_id=$model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'cates'=>$cate,'detail'=>$detail]);
    }
    public function actionEdit($id){
        $model=Article::findOne(['id'=>$id]);
        $detail=ArticleDetail::findOne(['article_id'=>$id]);
        $cate=ArticleCategory::find()->all();
        $cate=ArrayHelper::map($cate,'id','name');
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $detail->load($request->post());
            if($model->validate()){
                $model->save();
                $detail->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'cates'=>$cate,'detail'=>$detail]);
    }

    public function actionDel($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['article/index']);
    }
    public function actionShow($id){
        $model=Article::findOne(['id'=>$id]);
        return $this->render('show',['model'=>$model]);
    }

}
