<?php

namespace frontend\controllers;



use frontend\models\LoginForm;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    public $layout="login";
    public function actionRegister()
    {
        $model=new Member();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
               $model->save(false);
            \Yii::$app->session->setFlash('seccuss','注册成功');
            return $this->redirect('index');
        }
        return $this->render('register',['model'=>$model]);
    }
    public function actionLogin()
    {
        $model=new  LoginForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
               $model->login();
            \Yii::$app->session->setFlash('seccuss','登陆成功');
            return $this->redirect('index');
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('seccuss','注销成功');

    }
    public function actionNow(){
        $identity = \Yii::$app->user->identity;
        var_dump($identity);
    }

}
