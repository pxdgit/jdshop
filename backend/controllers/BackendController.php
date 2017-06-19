<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18 0018
 * Time: 14:25
 */

namespace backend\controllers;


use backend\components\RbacFilter;
use yii\base\Controller;

class BackendController extends \yii\web\Controller
{
    public function behaviors(){
        return [
            'accessFilter'=>[
                'class'=>RbacFilter::className(),
            ],
        ];
    }
}