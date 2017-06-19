<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18 0018
 * Time: 10:28
 */

namespace backend\components;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action){
        $user=\Yii::$app->user;
        if(!$user->can($action->uniqueID)){//当前用户是否拥有此权限（此权限用当前路由表示
            if(\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl);
            }
                throw new HttpException('404','对不起，您没有此权限');
        }
        return parent::beforeAction(1);
    }

}