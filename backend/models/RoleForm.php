<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16 0016
 * Time: 11:30
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions=[];
    public function rules(){
       return [
          [['name','description'],'required'],
           ['permissions','safe'],
       ];
    }
    public function attributeLabels(){
        return [
            'name'=>'角色名',
            'description'=>'介绍',
            'permissions'=>'权限'
           ];
    }
    public static function getpermissions(){
       $permissions=\Yii::$app->authManager->getPermissions();//获取所有权限
       return ArrayHelper::map($permissions,'name','description');
    }
    public function addrole(){
        $authmanager=\Yii::$app->authManager;
        if($authmanager->getRole($this->name)){
            $this->addError('name','此角色已存在');
        }else{
            if($role=$authmanager->createRole($this->name)){
                $role->description=$this->description;
                $authmanager->add($role);
                foreach ($this->permissions as $name){
                       $permission=$authmanager->getPermission($name);
                       $authmanager->addChild($role,$permission);
                    return true;
                }
            }
        }
        return false;
    }
    public function loaddata($role){
        $this->name=$role->name;
        $this->description=$role->description;
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;//重点,将name赋值给表单权限数组
        }
    }
    public function editrole($name){
        $authmanager=\Yii::$app->authManager;
        $role=$authmanager->getRole($name);
        $role->name=$this->name;
        $role->description=$this->description;
        if($name!=$this->name && $authmanager->getRole($this->name)){//说明修改了角色名//判断修改后的角色是否已存在
                $this->addError('name','此角色已存在');
        }else{
            if($authmanager->update($name,$role)){
                $authmanager->removeChildren($role);//删除该角色所有权限
                foreach ($this->permissions as $name){
                    $permission=$authmanager->getPermission($name);
                    $authmanager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }


}