<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=User::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd()
    {
        $model=new User(['scenario'=>User::SCENARIO_ADD]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->password==$model->repassword){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            $model->save();
            \Yii::$app->session->setFlash('seccuess','添加成功');
            return $this->redirect(['user/index']);}
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        $model=User::findOne(['id'=>$id]);
        $model->scenario=User::SCENARIO_EDIT;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            $model->save();
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=User::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        return $this->redirect(['user/index']);
    }
    public function actionLogin(){
        $model=new LoginForm();//new一个登录验证的表单模型
        if($model->load(\Yii::$app->request->post()) && $model->validate()){//接收数据并验证
            \Yii::$app->session->setFlash('success','登陆成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('login',['loginform'=>$model]);

    }
    public function actionLoginout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/index']);

    }
    public function actionNow(){
        $identity = \Yii::$app->user->identity;
        var_dump($identity);
    }

}
