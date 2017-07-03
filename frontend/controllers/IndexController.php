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
//        $cl = new SphinxClient();
//        $cl->SetServer('127.0.0.1', 9312);
////$cl->SetServer ( '10.6.0.6', 9312);
////$cl->SetServer ( '10.6.0.22', 9312);
////$cl->SetServer ( '10.8.8.2', 9312);
//        $cl->SetConnectTimeout(10);
//        $cl->SetArrayResult(true);
//// $cl->SetMatchMode ( SPH_MATCH_ANY);
//        $cl->SetMatchMode(SPH_MATCH_ALL);
//        $cl->SetLimits(0, 1000);
//        $res = $cl->Query($search->name, 'goods');//shopstore_search
////            var_dump($res);exit;
//        if(!isset($res['matches'])){
//            $query->where(['id'=>0]);
//        }else{
//            $ids=ArrayHelper::map($res['matches'],'id','id');
//            $query->where(['in','id',$ids]);
//        }
//        $firstcates=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index');
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