<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 13:19
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Num;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class CartController extends  Controller
{
    public $layout='cart';
    public $num=[];
    public function actionAddCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        if (!$goods) {
            throw new NotFoundHttpException('没有此商品');
        }
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            if ($cookies->get('cart')) {//cookie中是否已有数据//有则取出反序列化为数组
                $cart = unserialize($cookies->get('cart'));
            } else {
                $cart = [];
            }
            $cookies = \Yii::$app->response->cookies;

            if (key_exists($goods_id, $cart)) {//此商品是否存在，存在则增加数量
                $cart[$goods_id] += $amount;
            } else {//不存在则添加一个数组元素
                $cart[$goods_id] = $amount;
            }
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => serialize($cart)
            ]);
            $cookies->add($cookie);
        } else {
            if ($model = Cart::findOne(['member_id' => \Yii::$app->user->id, 'goods_id' => $goods_id])) {
                $model->amount += $amount;
            } else {
                $model = new Cart();
                $model->member_id = \Yii::$app->user->id;
                $model->goods_id = $goods_id;
                $model->amount += $amount;
            }
            $model->save();

        }
        return $this->redirect(['cart/shop-cart']);
    }

    public function actionUpdate(){
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);
        if(!$goods){
            throw new NotFoundHttpException('没有此商品');
        }
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie){//cookie中是否已有数据//有则取出反序列化为数组
                $cart=unserialize($cookie->value);
            }else{
                $cart=[];
            }
            $cookies=\Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)){unset($cart[$goods_id]);}
            }

            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
              if($model=Cart::findOne(['member_id'=>\Yii::$app->user->id,'goods_id'=>$goods_id])){
                 if($amount){
                     $model->amount=$amount;
                     $model->save();
                 }else{
                    $model->delete();
                }
            }else{
                  throw new NotFoundHttpException('没有此记录，无法操作');
              }
        }
    }
    public function actionShopCart(){
        $model=[];
        if($member_id=\Yii::$app->user->id){
            $allgoods=Cart::findAll(['member_id'=>$member_id]);
            foreach ($allgoods as $v){
                $goods=Goods::findOne(['id'=>$v->goods_id])->attributes;
                $goods['amount']=$v->amount;
                $model[]=$goods;
            }
        }else{
            $cookies=\Yii::$app->request->cookies;
            if($cookies->get('cart')){//cookie中是否已有数据//有则取出反序列化为数组
                $cart=unserialize($cookies->get('cart'));
            }else{
                $cart=[];
            }
            foreach ($cart as $k=>$v){
                $goods=Goods::findOne(['id'=>$k])->attributes;
                $goods['amount']=$v;
                $model[]=$goods;
            }
        }
        return $this->render('shopcart',['model'=>$model]);
    }


    public function actionOrderMsg(){
        if(\Yii::$app->user->identity){
            $model=new Order();
            $goods=Cart::findAll(['member_id'=>\Yii::$app->user->id]);
            $allgoods=[];
            foreach ($goods as $v){
                $goodsone=Goods::findOne(['id'=>$v->goods_id])->attributes;
                $goodsone['amount']=$v->amount;
                $allgoods[]=$goodsone;
            }
            if($model->load(\Yii::$app->request->post())&&$model->validate()){
                $transaction=\Yii::$app->db->beginTransaction();
                try{
                        $model->save();
                        foreach ($allgoods as $onegoods){
                            $order_goods=new OrderGoods();
                            $order_goods->order_id=$model->id;
                            $order_goods->goods_id=$onegoods['id'];
                            $order_goods->goods_name=$onegoods['name'];
                            $order_goods->logo=$onegoods['logo'];
                            $order_goods->price=$onegoods['shop_price'];
                            $order_goods->amount=$onegoods['amount'];
                            if($order_goods->amount>$onegoods['stock']) {
//                                throw new \yii\base\Exception('库存不足');
                                throw new Exception('库存不足');
                            }
                            $order_goods->total=$order_goods->price*$order_goods->amount;
                            $order_goods->save();
                            $newgoods=Goods::findOne(['id'=>$onegoods['id']]);
                            $newgoods->stock=$onegoods['stock']-$order_goods->amount;
                            $newgoods->save();
                        }
                        foreach(Cart::findAll(['member_id'=>\Yii::$app->user->id]) as $cart){
                            $cart->delete();
                        }
                        $this->redirect(['cart/order-fin']);
                    $transaction->commit();//提交事务会真正的执行数据库操作
                }catch (Exception $e) {
                    $transaction->rollback();//如果操作失败, 数据回滚
                }
            }
              $alladdress=Address::findAll(['member_id'=>\Yii::$app->user->id]);


        return $this->render('ordermsg',['model'=>$model,'alladdress'=>$alladdress,'allgoods'=>$allgoods]);}
        else{
            $this->redirect(['/member/login']);
        }
    }
    public function actionOrderFin(){
        return $this->render('orderfin');
    }
    public function actionShow(){
        $cookies=\Yii::$app->request->cookies;
        if($cookies->get('cart')){//cookie中是否已有数据//有则取出反序列化为数组
            $cart=unserialize($cookies->get('cart'));
        }else{
            $cart=[];
        }
        var_dump($cart);
    }
    public function actionGame(){
        $this->layout=false;
        $model=new Num();
        $arr=[];
        for($i=1;$i<=10;$i++){
            $tmp = rand(1,9);
            if (!in_array($tmp, $arr)) {
                $arr[]=$tmp;
            }
        }
        $num=array_slice($arr,0,4);
        $cache=\Yii::$app->cache;
        if($cache->get('num')==null){
            $cache->set('num',$num);
        }
        var_dump($cache->get('num'));
        $nums=$cache->get('num');
        $suma=0;
        $sumb=0;
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            foreach ($model as $k=>$num){
                if(in_array($num,$nums)){
                    $suma+=1;
                }
                $n=substr($k,-1);
              if($num==$nums[$n]){
                  $sumb+=1;
              }
            }
        }
        return $this->render('game',['model'=>$model,'suma'=>$suma,'sumb'=>$sumb]);
    }

}