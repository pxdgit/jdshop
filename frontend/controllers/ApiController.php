<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29 0029
 * Time: 11:22
 */

namespace frontend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
//1.会员
//-会员注册
    public function actionRegister()
    {
        if (\Yii::$app->request->isPost) {
            $member = new Member();
            $member->username = \Yii::$app->request->post('username');
            $member->password = \Yii::$app->request->post('password');
            $member->repassword = \Yii::$app->request->post('repassword');
            $member->email = \Yii::$app->request->post('email');
            $member->tel = \Yii::$app->request->post('tel');
            $member->code=\Yii::$app->request->post('code');
            $member->smscaptcha=\Yii::$app->request->post('smscaptcha');
            $member->status = 1;
            $member->agree = \Yii::$app->request->post('agree');
            if ($member->validate()) {
                $member->save(false);
                return ['status' => 1, 'msg' => '注册成功'];
            } else {
                return ['status' => -1, 'msg' => $member->getErrors()];
            }
        }
    }

//-会员登录
    public function actionLogin()
    {
        if (\Yii::$app->request->isPost) {
            $login = new LoginForm();
            $login->username = \Yii::$app->request->post('username');
            $login->password = \Yii::$app->request->post('password');
            $login->rememberme = \Yii::$app->request->post('rememberme');
            if ($login->login()) {
                return ['status' => 1, 'msg' => '登陆成功'];
            } else {
                return ['status' => 1, 'msg' => '登陆失败'];
            }
        }
    }

//-会员密码修改
    public function actionChangePwd()
    {
        if (\Yii::$app->request->isPost) {
            $member = Member::findOne(['id' => \Yii::$app->user->id]);
            $member->password = \Yii::$app->request->post('password');
            $member->newpassword = \Yii::$app->request->post('newpassword');
            if (\Yii::$app->security->validatePassword($member->password, $member->password_hash)) {
                $member->password_hash = \Yii::$app->security->generatePasswordHash($member->newpassword);
                if ($member->save(false)) {
                    return ['status' => 1, 'msg' => '修改密码成功'];
                } else {
                    return ['status' => -1, 'msg' => '密码修改失败'];
                }
            } else {
                return ['status' => -1, 'msg' => '旧密码错误'];
            }
        }
    }

//获得当前登陆用户
    public function actionGetUser()
    {
        if ($user = \Yii::$app->user->identity) {
            return ['status' => 1, 'user' => $user];
        } else {
            return ['status' => -1, 'msg' => '用户未登录'];
        }
    }

    //注销当前登陆用户
    public function actionUserLogout()
    {
        \Yii::$app->user->logout();
        return ['status' => 1, 'msg' => '注销成功'];
    }

//增加收货地址
    public function actionAddAddress()
    {
        if (\Yii::$app->request->isPost) {
            $model = new Address();
            $model->addressee = \Yii::$app->request->post('addressee');
            $model->province = \Yii::$app->request->post('province');
            $model->city = \Yii::$app->request->post('city');
            $model->area = \Yii::$app->request->post('area');
            $model->address = \Yii::$app->request->post('address');
            $model->tel = \Yii::$app->request->post('tel');
            $model->status = \Yii::$app->request->post('status');
            if ($model->validate()) {
                $model->save();
                return ['status' => 1, 'msg' => '地址增加成功'];
            } else {
                return ['status' => 1, 'msg' => $model->getErrors()];
            }
        }
    }

//修改收货地址
    public function actionEditAddress()
    {
        if (\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->post('id');
            $model = Address::findOne(['id' => \Yii::$app->request->post('id')]);
            $model->addressee = \Yii::$app->request->post('addressee');
            $model->province = \Yii::$app->request->post('province');
            $model->city = \Yii::$app->request->post('city');
            $model->area = \Yii::$app->request->post('area');
            $model->address = \Yii::$app->request->post('address');
            $model->tel = \Yii::$app->request->post('tel');
            $model->status = \Yii::$app->request->post('status');
            if ($model->validate()) {
                $model->save();
                return ['status' => 1, 'msg' => '地址修改成功'];
            } else {
                return ['status' => 1, 'msg' => $model->getErrors()];
            }
        }
    }

//删除收货地址
    public function actionDelAddress($id)
    {
        $model = Address::findOne(['id' => $id]);
        if ($model) {
            $model->delete();
            return ['status' => 1, 'msg' => '地址删除成功'];
        }
        return ['status' => -1, 'msg' => '没有这个地址，删除失败'];
    }

//收货地址列表
    public function actionAddressIndex()
    {
        $models = Address::find()->all();
        return ['status' => -1, 'alladdress' => $models];
    }

//商品分类
//-获取所有商品分类
    public function actionGetAllCategory(){
        $categorys=GoodsCategory::find()->asArray()->all();
        return ['status' => 1, 'categorys' => $categorys];
    }
//-获取某分类的所有子分类
public function actionGetChildCategory(){
    $category_id=\Yii::$app->request->get('category');
    $category=GoodsCategory::findOne(['id'=>$category_id]);
    $child_category = GoodsCategory::find()->where(['tree' => $category->tree])->andWhere(['>', 'lft', $category->lft])->andWhere(['<', 'rgt', $category->rgt])->asArray()->all();//得到子分类，同一树，左值小于，右值大于
    return ['status' => 1, 'child_category' => $child_category];

}
//-获取某分类的父分类
public function actionGetParentCategory(){
    $category_id=\Yii::$app->request->get('category');
    $category=GoodsCategory::findOne(['id'=>$category_id]);
    $parent=GoodsCategory::findOne(['id'=>$category->parent_id]);
    return ['status' => 1, 'parent_category' => $parent];

}


//根据品牌获取商品
    public function actionGetGoodsByBrand()
    {
        if ($brand_id = \Yii::$app->request->get('brand_id')) {
            $allgoods = \backend\models\Goods::find()->where(['brand_id' => $brand_id])->asArray()->all();
            return ['status' => 1, 'error_code' => '', 'allgoods' => $allgoods];
        }
        return ['status' => -1, 'msg' => '缺少品牌id参数'];
    }

//根据商品分类获取商品
    public function actionGetGoodsByCategory()
    {
        $per_size=\Yii::$app->request->get('per_size');
        $page=\Yii::$app->request->get('page');
        $goods_category_id=\Yii::$app->request->get('goods_category_id');
        $goods_category=GoodsCategory::findOne(['id'=>$goods_category_id]);
        $query=Goods::find();
        if($goods_category){
            switch ($goods_category->depth){
                case 2: $query->andWhere(['goods_category_id'=>$goods_category_id]);break;
                case 1: $ids=ArrayHelper::map(GoodsCategory::findAll(['parent_id'=>$goods_category_id]),'id','id');
                        $query->andWhere(['in','goods_category_id',$ids]);
                        break;
                case 0: $ids=ArrayHelper::map($goods_category->leaves()->asArray()->all(),'id','id');
                        $query->andWhere(['in','goods_category_id',$ids]);
                        break;
            }
        }else{
            return ['status' => -1, 'msg' => '没有这个分类'];
        }
        $per_size=isset($per_size)?$per_size:1;//每页记录数
        $page=isset($page)?$page:1;//当前页码

        $total=$query->count();
        $goods=$query->offset($per_size*($page-1))->limit($per_size)->all();
        return ['status' => 1, 'page'=>$page,'total'=>$total,'per_size'=>$per_size,'allgoods'=>$goods];
    }

//获取文章分类
    public function actionGetArticleCategory()
    {
        $models = ArticleCategory::find()->all();
        return ['status' => 1, 'categorys' => $models];
    }

//获取某文章分类下文章
    public function actionGetArticleByCategory($article_category_id)
    {
        $models = Article::findAll(['article_category_id' => $article_category_id]);
        return ['status' => 1, 'articles' => $models];
    }

//获取某文章所属分类
    public function actionGetCategoryByArticle()
    {
        $article_id=\Yii::$app->request->get('article_id');
        $Article = Article::findOne(['id' => $article_id]);
        $model = ArticleCategory::find()->where(['id' => $Article->article_category_id])->asArray()->one();
        return ['status' => 1, 'category' => $model];
    }

//添加商品到购物车
    public function actionAddCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = \backend\models\Goods::findOne(['id' => $goods_id]);
        if (!$goods) {
            return ['status' => -1, 'msg' => '没有此商品'];
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
            return ['status' => 1, 'msg' => '加入购物车成功'];
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
        return ['status' => 1, 'msg' => '加入购物车成功'];
    }

//修改购物车中的商品数量
    public function actionUpdateCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = \backend\models\Goods::findOne(['id' => $goods_id]);
        if (!$goods) {
            return ['status' => -1, 'msg' => '没有此记录，无法修改'];
        }
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie) {//cookie中是否已有数据//有则取出反序列化为数组
                $cart = unserialize($cookie->value);
            } else {
                $cart = [];
            }
            $cookies = \Yii::$app->response->cookies;
            if ($amount) {
                $cart[$goods_id] = $amount;
            } else {
                if (key_exists($goods['id'], $cart)) {
                    unset($cart[$goods_id]);
                }
            }
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => serialize($cart)
            ]);
            $cookies->add($cookie);
                return ['status' => 1, 'msg' => '商品数量修改成功'];

        } else {
            if ($model = Cart::findOne(['member_id' => \Yii::$app->user->id, 'goods_id' => $goods_id])) {
                    $model->amount = $amount;
                    $model->save();
                    return ['status' => 1, 'msg' => '商品数量修改成功'];
            } else {
                return ['status' => -1, 'msg' => '没有此记录，无法修改'];
            }
        }
    }
//删除购物车中的某商品
    public function actionDelOneGoodsFromCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $goods=\backend\models\Goods::findOne(['id'=>$goods_id]);
        if($goods) {
            if (\Yii::$app->user->isGuest) {
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');
                if ($cookie) {//cookie中是否已有数据//有则取出反序列化为数组
                    $cart = unserialize($cookie->value);
                } else {
                    return ['status' => -1, 'msg' => '购物车中无商品，无法操作'];
                }
                $cookies = \Yii::$app->response->cookies;
                if (key_exists($goods['id'], $cart)) {
                        unset($cart[$goods_id]);
                 }else{
                    return ['status' => -1, 'msg' => '购物车中无此商品'];
                }
                $cookie = new Cookie([
                    'name' => 'cart',
                    'value' => serialize($cart)
                ]);
                $cookies->add($cookie);
                return ['status' => 1, 'msg' => '删除购物车商品成功'];
            } else {
                if ($model = Cart::findOne(['member_id' => \Yii::$app->user->id, 'goods_id' => $goods_id])) {
                        $model->delete();
                        return ['status' => 1, 'msg' => '删除购物车商品成功'];
                } else {
                    return ['status' => -1, 'msg' => '没有此记录，无法删除'];
                }
            }
        }else{
            return ['status' => -1, 'msg' => '此商品无记录'];
        }
    }

//清空购物车
    public function actionEmptyCart()
    {
        if (\Yii::$app->user->identity) {
            $carts = Cart::findAll(['member_id' => \Yii::$app->user->id]);
            if ($carts) {
                foreach ($carts as $cart) {
                    $cart->delete();
                }
                return ['status' => 1, 'msg' => '购物车清空成功'];
            } else {
                return ['status' => -1, 'msg' => '该用户购物车中暂无商品'];
            }
        } else {
            $cookies = \Yii::$app->response->cookies;
            $cookie = $cookies->remove('cart');
            return ['status' => 1, 'msg' => '购物车清空成功'];
        }


//        $cookies = \Yii::$app->request->cookies;
//        if ($cookies->get('cart')) {//cookie中是否已有数据//有则取出反序列化为数组
//            $cart = unserialize($cookies->get('cart'));
//            foreach ($cart as $k => $v) {
//                if ($cart = Cart::findOne(['goods_id' => $k, 'member_id' => \Yii::$app->user->id])) {
//                    $cart->amount += $v;
//                } else {
//                    $cart = new Cart();
//                    $cart->goods_id = $k;
//                    $cart->amount = $v;
//                    $cart->member_id = \Yii::$app->user->id;
//                }
//
//                $cart->save();
//            }
//            $cookies = \Yii::$app->response->cookies;
//            $cookies->remove('cart');
//        }
    }

//获取购物车中全部商品
    public function actionGetGoodsByCart()
    {
        if(\Yii::$app->user->identity) {
            $carts = Cart::findAll(['member_id' => \Yii::$app->user->id]);
            if ($carts) {
                foreach ($carts as $cart) {
                    $goods[] = \backend\models\Goods::findOne(['id' => $cart->goods_id]);
                }
                return ['status' => 1, 'allgoods' => $goods];
            } else {
                return ['status' => -1, 'msg' => '购物车没有商品','from'=>'数据库'];
            }
        }else{
            $cookies=\Yii::$app->request->cookies;
            if($cookies->get('cart')){//cookie中是否已有数据//有则取出反序列化为数组
                $cart=unserialize($cookies->get('cart'));
            }else{
                return ['status' => -1, 'msg' => '购物车没有商品'];
            }
            foreach ($cart as $k=>$v){
                $goods=Goods::findOne(['id'=>$k])->attributes;
                $goods['amount']=$v;
                $model[]=$goods;
            }
            return ['status' => 1, 'allgoods' => $model,'from'];
        }
    }

//获取支付方式
    public function actionGetPayment()
    {
//        $payment_id=\Yii::$app->request->post('payment_id');
//        return Order::$payments[$payment_id-1];
        return ['status' => 1, 'payment' => Order::$payments];

    }

//获取送货方式
    public function actionGetDelivery()
    {
        return ['status' => 1, 'delivers' => Order::$delivers];

    }

//提交订单
    public function actionOrderMsg()
    {
        if (\Yii::$app->user->identity) {
            $model = new Order();
            $goods = Cart::findAll(['member_id' => \Yii::$app->user->id]);
            $allgoods = [];
            foreach ($goods as $v) {
                $goodsone = \backend\models\Goods::findOne(['id' => $v->goods_id])->attributes;
                $goodsone['amount'] = $v->amount;
                $allgoods[] = $goodsone;
            }
            if (\Yii::$app->request->isPost) {
                $model->name = \Yii::$app->request->post('name');
                $model->province = \Yii::$app->request->post('province');
                $model->city = \Yii::$app->request->post('city');
                $model->area = \Yii::$app->request->post('area');
                $model->address = \Yii::$app->request->post('address');
                $model->tel = \Yii::$app->request->post('tel');
                $model->delivery_id = \Yii::$app->request->post('delivery_id');
                $model->delivery_name = \Yii::$app->request->post('delivery_name');
                $model->delivery_price = \Yii::$app->request->post('delivery_price');
                $model->payment_id = \Yii::$app->request->post('payment_id');
                $model->payment_name = \Yii::$app->request->post('payment_name');
                $model->total = \Yii::$app->request->post('total');
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if($model->validate()){
                        $model->save(false);
                    }
                    foreach ($allgoods as $onegoods) {
                        $order_goods = new OrderGoods();
                        $order_goods->order_id = $model->id;
                        $order_goods->goods_id = $onegoods['id'];
                        $order_goods->goods_name = $onegoods['name'];
                        $order_goods->logo = $onegoods['logo'];
                        $order_goods->price = $onegoods['shop_price'];
                        $order_goods->amount = $onegoods['amount'];
                        if ($order_goods->amount > $onegoods['stock']) {
                            throw new \yii\base\Exception('库存不足');
                        }
                        $order_goods->total = $order_goods->price * $order_goods->amount;
                        $order_goods->save();
                        $newgoods = \backend\models\Goods::findOne(['id' => $onegoods['id']]);
                        $newgoods->stock = $onegoods['stock'] - $order_goods->amount;
                    }

                    foreach (Cart::findAll(['member_id' => \Yii::$app->user->id]) as $cart) {
                        $cart->delete();
                    }
                    $transaction->commit();//提交事务会真正的执行数据库操作
                    return ['status' => 1, 'msg' => '提交订单成功'];

                } catch (Exception $e) {
                    $transaction->rollback();//如果操作失败, 数据回滚
                    return ['status' => -1, 'msg' => '库存不足'];
                }
            }
        } else {
            return ['status' => -1, 'msg' => '用户未登录'];
        }
    }

//获取当前用户订单列表
    public function actionGetMemberOrder()
    {
        $orders = Order::findAll(['member_id' => \Yii::$app->user->id]);
        return ['status' => 1, 'orders' => $orders];

    }

//取消订单
    public function actionOffMemberOrder()
    {
        $id = \Yii::$app->request->get('order_id');
        if ($id) {
            $order = Order::findOne(['id' => $id]);
            $order->status = 0;
            $order->save();
            return ['status' => 1, 'msg' => '订单取消成功'];
        } else {
            return ['status' => -1, 'msg' => '没有此订单'];
        }
    }

//验证码
    public function actions(){
        return [
            'captcha'=>[
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //初始化：实例化好类后给类属性赋值
                'minLength'=>4,//最小长度
                'maxLength'=>4,//最大长度
            ],
        ];
        //可通过 http://www.jx.com/api/captcha调用此验证码接口
        //  http://www.jx.com/api/captcha?refresh=1可刷新验证码
        //  刷新验证码后返回的           http://www.jx.com/api/captcha?v=595750754da0f
    }
//手机验证码
    public function actionSms()
    {
        $code = rand(1000, 9000);
        $tel = \Yii::$app->request->post('tel');
        if (!preg_match('/^1[3578]\d{9}$/', $tel)) {
            return ['status' => -1, 'msg' => '手机号格式不正确'];
        }
        $stime =time()-\Yii::$app->cache->get('time_' . $tel);
        if ($stime<60) {
            return ['status' => -1, 'msg' => '短信验证码发送失败,请在'.(60-$stime).'后重试'];

        }
//        $result=\Yii::$app->sms->setNum($tel)->setParam(['code'=>$code])->send();
        \Yii::$app->cache->set('tel_' . $tel, $code, 2 * 60);
        \Yii::$app->cache->set('time_' . $tel,time(), 60);
        if ($result = 1) {
            return ['status' => 1, 'msg' => '短信验证码发送成功', 'code' => $code];

        } else {
            return ['status' => -1, 'msg' => '短信验证码发送失败'];
        }

    }

//文件上传
    public function actionUploadImg(){
        $img=UploadedFile::getInstanceByName('img');
        $filename='/images/api/'.uniqid().'.'.$img->extension;
        $img->saveAs(\Yii::getAlias('@webroot').$filename);
        return ['status' => 1, 'msg' => '图片上传成功', 'path' =>$filename];
    }













}