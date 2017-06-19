<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends BackendController
{

    //文章分类列表
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        $total=$query->count();//获取总记录数
        $page=new Pagination([//实例化一个
            'totalCount'=>$total,
            'defaultPageSize'=>'10'
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();//相当于limit   start    pagesize
        return $this->render('index', ['model' => $model,'page'=>$page]);
    }

    //添加文章分类
    public function actionAdd(){
        $model = new ArticleCategory();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //修改文章分类
    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);//根据id找到当前记录
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    //删除文章分类
    public function actionDel($id){
        $model = ArticleCategory::findOne(['id' => $id]);
        $article=Article::findOne(['article_category_id'=>$id]);//查看当前分类下是否存在有文章
        if($article!=null){//有文章则不可删除
            \Yii::$app->session->setFlash('danger', '删除失败，该分类下有文章');
        }else{
            $model->status=-1;
            $model->save();
            \Yii::$app->session->setFlash('success', '删除成功');
        }
        return $this->redirect(['article-category/index']);
    }
    //显示该文章分类下的文章。
    public function actionShow($id){
        $model=Article::findAll(['article_category_id'=>$id]);
        return $this->render('show', ['model' => $model]);
    }
}
