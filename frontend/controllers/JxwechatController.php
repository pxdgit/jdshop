<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/4 0004
 * Time: 9:17
 */

namespace frontend\controllers;


use yii\web\Controller;
use EasyWeChat\Foundation\Application;

class JxwechatController extends Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex(){

        $app = new Application(\Yii::$app->params['wechat']);
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            return "您好！欢迎关注我!";
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
}