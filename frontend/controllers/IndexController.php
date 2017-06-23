<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 19:49
 */

namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Index;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout='index';
    public function actionIndex(){
        $firstcates=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['firstcates'=>$firstcates]);
    }
}