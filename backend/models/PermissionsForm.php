<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16 0016
 * Time: 9:55
 */

namespace backend\models;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class PermissionsForm extends Model
{
    public $name;
    public $description;
    public function rules(){
        return [
            [['name','description'],'required'],
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'权限名称',
            'description'=>'描述'
        ];
    }
    public function addpermission(){
        $authmanager=\Yii::$app->authManager;
        if($authmanager->getPermission($this->name)){
           $this->addError('name','此权限已存在');
        }else{
            $permission=$authmanager->createPermission($this->name);
            $permission->description=$this->description;
            $authmanager->add($permission);
            return true;
        }
    }
    public function loaddate($permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }
    public function updatepermission($name){
        $authmanager=\Yii::$app->authManager;
            if($this->name!=$name){
                if($authmanager->getPermission($this->name)) {
                    $this->addError('name', '已存在此权限');
                    return false;
                }
            }
             $permission=$authmanager->getPermission($name);
             $permission->name=$this->name;
             $permission->description=$this->description;
             $authmanager->update($name,$permission);
             return true;
    }
}