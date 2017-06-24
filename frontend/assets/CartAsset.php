<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 13:12
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        "style/fillin.css",
        'style/footer.css',
        "style/cart.css",
        "style/success.css"
    ];
    public $js = [
        "js/jquery-1.8.3.min.js",
        "js/cart2.js",
        "js/cart1.js"
    ];
    public $depends = [
        '\yii\web\JqueryAsset',
    ];
}
