<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = ArticleCategory::find()->all();
        return $this->render('index', ['model' => $model]);
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
            \Yii::$app->session->setFlash('danger', '删除失败，该分类下有子分类');
        }else{
            $model->status=-1;
            $model->save();
            \Yii::$app->session->setFlash('success', '删除成功');
        }

        return $this->redirect(['article-category/index']);
    }
}
