<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/3 0003
 * Time: 17:35
 */

namespace frontend\controllers;


use EasyWeChat\Message\News;
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

    }
    public function actionOrder(){
        $app = new Application(\Yii::$app->params['wechat']);
        //$response = $app->oauth->scopes(['snsapi_base'])//发起网页授权
        $response = $app->oauth->redirect();
        $response->send();
    }
    public function actionLogin(){

    }
    public function actionCallback(){
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
   // 对应微信的 OPENID\
        var_dump($user->getId());
    }
}