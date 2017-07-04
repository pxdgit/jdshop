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
use frontend\models\Member;
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
        var_dump(Member::findOne(['openid'=>$openid,'id'=>\Yii::$app->user->id]));
    }
    public function actionLogin(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null){
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->redirect();
            $response->send();
        }
        if(\Yii::$app->request->isPost){
            $member=Member::findOne(['username'=>\Yii::$app->request->post('username')]);
            if($member){
                if(\Yii::$app->security->validatePassword(\Yii::$app->request->post('password'),$member->password_hash)){
                    \Yii::$app->user->login($member);
                    Member::updateAll(['openid'=>$openid],'id='.$member->id);
//                    if($url=\Yii::$app->session->get('redirect')){
//                        return $this->redirect($url);
//                    }
                    echo '绑定成功,openid:'.$openid;
                    exit;
                }
            }
                echo '登陆失败';
        }
        return $this->renderPartial('login');
    }
    public function actionOrder(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null || Member::findOne(['openid'=>$openid])==null){
          \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
          return $this->redirect(['jxwechat/login']);
        }
    }
    public function actionAddress(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null || Member::findOne(['openid'=>$openid])==null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            return $this->redirect(['jxwechat/login']);
        }
    }
    public function actionEditPwd(){
        $openid=\Yii::$app->session->get('openid');
        if($openid==null || Member::findOne(['openid'=>$openid])==null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            return $this->redirect(['jxwechat/login']);
        }
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