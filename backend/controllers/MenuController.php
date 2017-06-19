<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class MenuController extends BackendController
{
    public function actionIndex()
    {
        $model=Menu::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new Menu();
        if($model->load(\Yii::$app->request->post() )&& $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect(['menu/index']);
        }
        $cates=[0=>'顶级菜单']+ArrayHelper::map(Menu::find()->all(),'id','label');
        return $this->render('add',['model'=>$model,'cates'=>$cates]);
    }
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post() )&& $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['menu/index']);
        }
        $cates=[0=>'顶级菜单']+ArrayHelper::map(Menu::find()->all(),'id','label');
        return $this->render('add',['model'=>$model,'cates'=>$cates]);
    }
    public function actionDel($id){
        $model=Menu::findOne(['id'=>$id]);
        $children=Menu::findOne(['parent_id'=>$id]);
        if($children){
            throw new NotFoundHttpException('该菜单下含有子菜单，无法删除');
        }
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['menu/index']);
    }
}
