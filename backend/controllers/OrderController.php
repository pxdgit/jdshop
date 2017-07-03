<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderGoods;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=Order::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionShow($id){
        $model=OrderGoods::findAll(['order_id'=>$id]);
        return $this->render('show',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Order::findOne(['id'=>$id]);
        $model->status=3;
        $model->save();
    }

}
