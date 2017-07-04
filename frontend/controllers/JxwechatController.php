<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/4 0004
 * Time: 9:17
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\Order;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

class JxwechatController extends Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex(){
        $app = new Application(\Yii::$app->params['wechat']);
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType){
                case 'text':
                    switch ($message->Content){
                        case '帮助':
                            return "发送 优惠、解除绑定 等信息 ，有惊喜哟";
                            break;
                        case '优惠':
                            $allgoods=Goods::find()->limit(5)->all();
                            foreach ($allgoods as $goods){
                                $news[]= new News([
                                    'title'       => $goods->name,
                                    'description' =>GoodsIntro::findOne(['goods_id'=>$goods->id])->content,
                                    'url'         => 'http://jx.penneyx.cn/goods/index?id='.$goods->id,
                                    'image'       => 'http://admin.penneyx.cn/'.$goods->logo,
                                ]);
                            }
                            return $news;
                            break;
                        case '解除绑定':
                            $openid=$message->FromUserName;//这个就是openid！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
                            if($openid==null || Member::findOne(['openid'=>$openid])==null){
                                return '您未绑定账户，若需绑定，请前往 http://jx.penneyx.cn/jxwechat/login';
                            }else{
                                $member=Member::findOne(['openid'=>$openid]);
                                $member->openid=null;
                                if($member->save(false)){
                                    return '解除绑定成功';
                                }
                            }
                            break;
                    }
//            * 用户发送【帮助】，回复以下信息“您可以发送 优惠、解除绑定 等信息”
//            * 用户发送【优惠】，效果和点击【促销商品】相同
//            * 用户发送【解除绑定】，如用户已绑定账户，则解绑当前openid，并回复解绑成功；否则回复请先绑定账户及绑定页面地址
                case 'event':
                    if($message->Event=='CLICK'){
                        if($message->EventKey=='promotiongoods'){
                            $allgoods=Goods::find()->limit(5)->all();
                            foreach ($allgoods as $goods){
                                $news[]= new News([
                                    'title'       => $goods->name,
                                    'description' =>GoodsIntro::findOne(['goods_id'=>$goods->id])->content,
                                    'url'         => 'http://jx.penneyx.cn/goods/index?id='.$goods->id,
                                    'image'       => 'http://admin.penneyx.cn/'.$goods->logo,
                                ]);
                            }
                                return $news;
                        }
                    }
                    break;
            }
        });
        $response = $server->serve();
        $response->send(); // Laravel 里请使用：return $response;

    }
    public function actionMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "促销商品",
                "key"  => "promotiongoods",
            ],
            [
                "type" => "view",
                "name" => "在线商城",
                "url"  => "http://jx.penneyx.cn/index",
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url"  => "http://jx.penneyx.cn/jxwechat/login"
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => "http://jx.penneyx.cn/jxwechat/order"
                    ],
                    [
                        "type" => "view",
                        "name" => "收货地址",
                        "url"  => "http://jx.penneyx.cn/jxwechat/address"
                    ],
                    [
                        "type" => "view",
                        "name" => "修改密码",
                        "url"  => "http://jx.penneyx.cn/jxwechat/edit-pwd"
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        var_dump($menu->all());
    }
    public function actionClean(){
        $openid=\Yii::$app->session->get('openid');
        var_dump($openid) ;var_dump(\Yii::$app->user->id);exit;
    }
    public function actionLogin(){
        $openid=\Yii::$app->session->get('openid');
        if(!Member::findOne(['openid'=>$openid])){
            if ($openid == null) {
                $app = new Application(\Yii::$app->params['wechat']);
                $response = $app->oauth->redirect();
                $response->send();
            }
           if (\Yii::$app->request->isPost) {
            $member = Member::findOne(['username' => \Yii::$app->request->post('username')]);
            if ($member) {
                if (\Yii::$app->security->validatePassword(\Yii::$app->request->post('password'), $member->password_hash)) {
                    \Yii::$app->user->login($member);
                    Member::updateAll(['openid' => $openid], 'id=' . $member->id);
                    if($url=\Yii::$app->session->get('redirect')){
                        \Yii::$app->session->remove('redirect');
                        return $this->redirect([$url]);
                    }
                    echo '绑定成功';
                    exit;
                }
            }
            echo '登陆失败';
           }
            return $this->renderPartial('login');
      }else{
            return '您已有绑定账户,请先解除绑定';

        }
    }
    public function actionOrder(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null || Member::findOne(['openid'=>$openid])==null){
          \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
          return $this->redirect(['jxwechat/login']);
        }
        $member=Member::findOne(['id'=>\Yii::$app->user->id,'openid'=>$openid]);
        $allorder=Order::find()->where(['member_id'=>$member->id])->all();
        return $this->renderPartial('order',['orders'=>$allorder]);
    }




    public function actionAddress(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null || Member::findOne(['openid'=>$openid])==null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            return $this->redirect(['jxwechat/login']);
        }
        $member=Member::findOne(['id'=>\Yii::$app->user->id,'openid'=>$openid]);
        $alladdress=Address::find()->where(['member_id'=>$member->id])->all();
        return $this->renderPartial('address',['addresses'=>$alladdress]);
    }




    public function actionEditPwd(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null || Member::findOne(['openid'=>$openid])==null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            return $this->redirect(['jxwechat/login']);
        }
        if(\Yii::$app->request->isPost){
            $request=\Yii::$app->request;
            if($request->post('newpassword')==$request->post('repassword')){
                $member=Member::findOne(['openid'=>$openid,'id'=>\Yii::$app->user->id]);
                if(\Yii::$app->security->validatePassword($request->post('password'),$member->password_hash)){
                     $member->password_hash=\Yii::$app->security->generatePasswordHash($request->post('newpassword'));
                     if($member->save(false)){
                         echo '修改成功';
                     }
                }else{
                    echo '原密码错误';
                }
            }else{
                echo '两次密码不一致';
            }

        }
        return $this->renderPartial('pwd');
    }




    public function actionCallback(){
        $app = new Application(\Yii::$app->params['wechat']);
        $user= $app->oauth->user();
            // 获取 OAuth 授权结果用户信息
//        var_dump($user->getId());
        \Yii::$app->session->set('openid',$user->getId());
        return $this->redirect(['jxwechat/login']);
    }
}