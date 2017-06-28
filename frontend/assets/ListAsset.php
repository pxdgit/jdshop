<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21 0021
 * Time: 9:39
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class ListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //加载五个css文件
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/home.css',
        'style/address.css',
        "style/order.css",
        'style/bottomnav.css',
        'style/footer.css',


        'style/list.css',
        'style/common.css',

        "style/goods.css",
        "style/jqzoom.css"


    ];
    public $js = [
        "js/jquery-1.8.3.min.js",
        "js/header.js",
        "js/home.js",
        'js/list.js',
        "js/goods.js",
        "js/jqzoom-core.js"
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
    ];
}
