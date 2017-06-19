<?php

namespace  backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;
class MenuWidget extends Widget
{
    //Widget被实例化后执行的代码
    public function init(){
        parent::init();
    }
    public function run(){
        NavBar::begin([//一个导航条开始
        'brandLabel' => '京西商城后台',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '首页', 'url' => ['/goods/index']],

//        ['label' => '文章分类', 'url' => ['/article-category/index']],
//        ['label' => '品牌分类', 'url' => ['/brand/index']],
//        ['label' => '修改密码', 'url' => ['/user/change-pwd']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' =>\Yii::$app->user->loginUrl];
    } else {
        $menuItems[] = ['label' => 'LogOut('.\Yii::$app->user->identity->username.')', 'url' =>['user/loginout']];
        $menuItems[] = ['label' => '修改密码', 'url' => ['user/change-pwd']];


//        $menuItems[] =['label'=>'用户管理','items'=>[
//            ['label'=>'用户添加','url'=>['user/add']],
//            ['label'=>'修改密码','url'=>['user/change-pwd']],
//        ]
//        ];
        $menus=Menu::findAll(['parent_id'=>0]);
        foreach ($menus as $menu){
            $item = ['label' =>$menu->label,'items'=>[//一级菜单显示
            ]];
           foreach ($menu->children  as $child){//利用一对多，获取一级分类下的子分类
               if(\Yii::$app->user->can($child->url)){//判断用户是否有子分类的权限【因为权限的名称是以路由的格式写的，url就是路由，所以可以判断当前用户是否有此权限】
                   $item['items'][]=['label' =>$child->label, 'url' =>[$child->url]];//将二级菜单写进items中
               }
            }
            if(!empty($item['items'])){//如果一级菜单有子菜单才显示，没有则不显示
                $menuItems[]=$item;
            }
        }
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();//导航条结束
    }
}