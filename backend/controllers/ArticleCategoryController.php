<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>'1'
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index', ['model' => $model,'page'=>$page]);
    }
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

    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
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
    public function actionDel($id){
        $model = ArticleCategory::findOne(['id' => $id]);
        $article=Article::findOne(['article_category_id'=>$id]);
        if($article!=null){
            \Yii::$app->session->setFlash('danger', '删除失败，该分类下有文章');
        }else{
            $model->status=-1;
            $model->save();
            \Yii::$app->session->setFlash('success', '删除成功');
        }
        return $this->redirect(['article-category/index']);
    }
    public function actionShow($id){
        $model=Article::findAll(['article_category_id'=>$id]);
        return $this->render('show', ['model' => $model]);
    }
}
