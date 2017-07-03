<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\LoginForm;
use backend\models\User;
use yii\base\Controller;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
{
    public function behaviors(){
        return [
            'rbac'=>[
               'class'=>RbacFilter::className(),
               'only'=>['index','add','edit','del','change-pwd','empty-pwd'],
            ]
        ];
    }
    //用户列表
    public function actionIndex()
    {
        $model=User::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    //添加用户
    public function actionAdd()
    {
        $model=new User(['scenario'=>User::SCENARIO_ADD]);
//        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            if($model->password==$model->repassword){
//            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
//            $model->save();
//            $model->addrole($model);
//            \Yii::$app->session->setFlash('seccuess','添加成功');
//            return $this->redirect(['user/index']);}
//        }
        return $this->render('add',['model'=>$model]);
    }
    //修改用户
    public function actionEdit($id)
    {
        $model=User::findOne(['id'=>$id]);
        $model->showroles();
        $model->scenario=User::SCENARIO_EDIT;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            $model->save();
            \Yii::$app->authManager->revokeAll($id);
            if($model->roles!=null){
                $model->addrole($model);
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除用户
    public function actionDel($id){
        $model=User::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        return $this->redirect(['user/index']);
    }
    //修改密码
    public function actionChangePwd(){
        $id= \Yii::$app->user->id;
        $model=User::findOne(['id'=>$id]);
        $model->scenario=User::SCENARIO_PWD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->newpassword);
            $model->save();
            \Yii::$app->session->setFlash('success','密码修改成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('pwd',['model'=>$model]);

    }
    //修改用户权限角色
    public function actionChangeRole($id){
        if(!$model=User::findOne(['id'=>$id])){
            throw new NotFoundHttpException('没有此用户');
        }else{
            $model->showroles();
            if($model->load(\Yii::$app->request->post())){
                \Yii::$app->authManager->revokeAll($id);
                $model->addrole($model);
                return $this->redirect(['user/index']);
            }
        }
            return $this->render('role',['model'=>$model]);
    }
    //登录
    public function actionLogin(){
        $model=new LoginForm();//new一个登录验证的表单模型
        if($model->load(\Yii::$app->request->post()) && $model->validate()){//接收数据并验证
            \Yii::$app->session->setFlash('success','登陆成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('login',['loginform'=>$model]);

    }
    //注销登录
    public function actionLoginout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/index']);

    }
    //当前用户
    public function actionNow(){
        $identity = \Yii::$app->user->identity;
        var_dump($identity);
    }
    public function actionEmptyPwd($id){
        $model=User::findOne(['id'=>$id]);
        $model->scenario=User::SCENARIO_EPWD;
        $model->password_hash='';
        $model->save(false);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->newpassword);
            $model->save();
            \Yii::$app->session->setFlash('success','密码重置成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('pwd',['model'=>$model]);
    }

}
