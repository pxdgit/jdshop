<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 13:19
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Cart;
use frontend\models\Order;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class CartController extends  Controller
{
    public $layout='cart';
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
        }else{
            if($model=Cart::findOne(['member_id'=>\Yii::$app->user->id,'goods_id'=>$goods_id])){
                $model->amount+=$amount;
            }else{
                $model=new Cart();
                $model->member_id=\Yii::$app->user->id;
                $model->goods_id=$goods_id;
                $model->amount+=$amount;
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
        return $this->render('ordermsg');
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
}