<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 19:49
 */

namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Goods;
use frontend\models\Index;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout='index';
    public function actionIndex(){
        $firstcates=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['firstcates'=>$firstcates]);
    }

    public function actionClean(){
        while(1){
            $orders=Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->all();
            foreach ($orders as $order){
                $order->status=0;
                $order->save();
                $allgoods=OrderGoods::find()->where(['order_id'=>$order->id])->all();
                foreach($allgoods as $ordergoods){
                    $goods=\backend\models\Goods::findOne(['id'=>$ordergoods->goods_id]);
                    $goods->stock+=$ordergoods->amount;
                    $goods->save();
                }
            }
            sleep(1);
        }
    }
}