<?php

namespace backend\controllers;

use backend\models\PermissionsForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{
    public function actionIndexPermissions()
    {
        $authmanager=\Yii::$app->authManager;
        $models=$authmanager->getPermissions();
        return $this->render('index-permissions',['models'=>$models]);
    }
    public function actionAddPermission(){
        $model=new PermissionsForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->addpermission()) {
                \Yii::$app->session->setFlash('success', '权限添加成功');
                return $this->redirect('index-permissions');
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionEditPermission($name){
        $model=new PermissionsForm();
        if(!$permission=\Yii::$app->authManager->getPermission($name)){//判断是否有这个权限，没有则抛出异常
            throw new NotFoundHttpException('无此权限，无法修改');
        }
        $model->loaddate($permission);
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                    if($model->updatepermission($name)){
                        \Yii::$app->session->setFlash('success','权限修改成功');
                        return $this->redirect('index-permissions');
                    }
            }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionDelPermission($name){
        $authmanager=\Yii::$app->authManager;
        $permission=$authmanager->getPermission($name);
        $authmanager->remove($permission);
        \Yii::$app->session->setFlash('success', '权限删除成功');
        return $this->redirect('index-permissions');
    }

    //角色role
    public function actionIndexRoles(){
        $models=\Yii::$app->authManager->getRoles();
        return $this->render('index-roles',['models'=>$models]);
    }
    public function actionAddRole(){
        $model=new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->addrole()) {
                \Yii::$app->session->setFlash('success', '角色添加成功');
                return $this->redirect('index-roles');
            }
        }
            return $this->render('add-role',['model'=>$model]);
    }
    public function actionEditRole($name){
        $model=new RoleForm();
        if(!$role=\Yii::$app->authManager->getRole($name)){
            throw new NotFoundHttpException('此角色不存在，无法修改');
        }else{
            $model->loaddata($role);
            if($model->load(\Yii::$app->request->post()) && $model->validate()) {
                if ($model->editrole($name)) {
                    \Yii::$app->session->setFlash('success', '角色修改成功');
                    return $this->redirect('index-roles');
                }
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDelRole($name){
        $authmanager=\Yii::$app->authManager;
        $role=$authmanager->getRole($name);
        $authmanager->remove($role);
        \Yii::$app->session->setFlash('success', '角色删除');
        return $this->redirect('index-roles');
    }


}
