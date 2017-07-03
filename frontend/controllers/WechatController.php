<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/3 0003
 * Time: 17:35
 */

namespace frontend\controllers;


use EasyWeChat\Message\News;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;


class WechatController extends Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex(){
        $app = new Application(\Yii::$app->params['wechat']);
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
//            return "您好！欢迎关注我!";
            switch ($message->MsgType){
                case 'text':
                    switch ($message->Content){
                        case '注册':
                                    $url=Url::to(['member/login'],true);
                                    return '点击注册'.$url;
                                    break;
                        case '最新活动':
                            //单图文，就是只new一个，返回一个 ；多图文就是new多个，返回数组
                                $news = new News([
                                    'title'       =>'满五十送一百',
                                    'description' => '走过路过别错过',
                                    'url'         => 'http://jx.penneyx.cn/index/index',
                                    'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                                    // ...
                                ]);
                                $news1 = new News([
                                    'title'       =>'满一百送两百',
                                    'description' => '走过路过别错过',
                                    'url'         => 'http://jx.penneyx.cn/index/index',
                                    'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                                    // ...
                                ]);
                                $news2 = new News([
                                    'title'       =>'满二百送三百',
                                    'description' => '走过路过别错过',
                                    'url'         => 'http://jx.penneyx.cn/index/index',
                                    'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                                    // ...
                                ]);
//                                return $news;//单图文返回
                            return [$news,$news1,$news2];
                            break;
                        default:
                            $xml=simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                            $weather='';
                            foreach ($xml as $city){
                                if($message->Content==$city['cityname']) {
                                    $weather = $city['stateDetailed'];
                                    break;
                                }
                            }
                            if($weather==''){
                                return $message->Content;
                            }
                            return  $message->Content.'的天气情况为：'.$weather;
                            break;
                            }
                    break;
                case 'event':
                    if($message->Event=='CLICK'){
                        if($message->EventKey=='newactivity'){
                            $news = new News([
                                'title'       =>'满五十送一百',
                                'description' => '走过路过别错过',
                                'url'         => 'http://jx.penneyx.cn/index/index',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                                // ...
                            ]);
                            $news1 = new News([
                                'title'       =>'满一百送两百',
                                'description' => '走过路过别错过',
                                'url'         => 'http://jx.penneyx.cn/index/index',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                                // ...
                            ]);
                            $news2 = new News([
                                'title'       =>'满二百送三百',
                                'description' => '走过路过别错过',
                                'url'         => 'http://jx.penneyx.cn/index/index',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                                // ...
                            ]);
//                                return $news;//单图文返回
                            return [$news,$news1,$news2];
                        }
                    }else{
                        return $message->Event.'类型的事件,key为：'.$message->EventKey;
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
                "name" => "最新活动",
                "key"  => "newactivity"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "查看订单",
                        "url"  => Url::to(['wechat/order'],true),
                    ],
                    [
                        "type" => "view",
                        "name" => "用户绑定",
                        "url"  => Url::to(['wechat/login'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "个人中心",
                        "url"  => Url::to(['wechat/user'],true)
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        var_dump($menu->all());
        }
    public function actionUser(){
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){//没有授权用户时，发起授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();           //发起网页授权
            $response->send();
        }
        var_dump($openid);//打印授权用户唯一表示
    }
    public function actionOrder()
    {
        $openid = \Yii::$app->session->get('openid');//从session获取微信用户openid
        if ($openid == null) {//没有绑定微信用户，则发起网页授权，在回调方法中将opendid存入session
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);//保存当前路由，以便在回调方法中跳转
            $app = new Application(\Yii::$app->params['wechat']);
            //$response = $app->oauth->scopes(['snsapi_base'])//发起网页授权
            $response = $app->oauth->redirect();
            $response->send();
        }
        $member = Member::findOne(['openid'=>$openid]);
        if($member == null){//该微信用户没有与商城用户绑定绑定
            return $this->redirect(['wechat/login']); //跳转到登陆（登陆商城账号）页面
        }else{
            $orders = Order::findAll(['member_id'=>$member->id]);
            var_dump($orders);
        }
    }
    public function actionLogin(){
        $openid = \Yii::$app->session->get('openid');//获取已授权用户openid
        if($openid == null){//没有授权用户时，发起授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();           //发起网页授权
            $response->send();
        }
        if(\Yii::$app->request->isPost){//
            $user = Member::findOne(['username'=>\Yii::$app->request->post('username')]);//验证用户名
            if($user && \Yii::$app->security->validatePassword(\Yii::$app->request->post('password'),$user->password_hash)){//验证密码
                \Yii::$app->user->login($user);//验证成功，登陆
                Member::updateAll(['openid'=>$openid],'id='.$user->id);//写入商城用户openid字段，以此将微信用户和商城用户关联
                if(\Yii::$app->session->get('redirect')) {//登陆若是通过其他页面跳转来，则跳回去
                    return $this->redirect([\Yii::$app->session->get('redirect')]);
                };
                echo '绑定成功';exit;
            }else{
                echo '登录失败';exit;
            }
        }
        return $this->renderPartial('login');

    }
    public function actionCallback(){
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();//获取已授权用户
   // 对应微信的 OPENID\
//        var_dump($user->getId());//获取授权用户的唯一表示openid
        \Yii::$app->session->set('openid',$user->getId());//将openid存入session
        return $this->redirect([\Yii::$app->session->get('redirect')]);//跳转到上一页面
    }
}